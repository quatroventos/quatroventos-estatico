<?php

namespace App\Services;

use App\Services\Mockup\HeadlessChrome;
use Illuminate\Support\Facades\File;
use RuntimeException;

/**
 * Fotografa seções do site público para servir de miniatura no seletor de seções do CMS.
 *
 * Cada alvo é a primeira seção de um tipo numa URL; o Chrome do servidor abre a URL pelo endereço local
 * da aplicação (cms.capture_url), rola até a seção e recorta só ela. Conteúdo não publicado entra pela rota
 * interna de captura (/__og/{token}), que renderiza o payload em cache.
 */
class SectionThumbnailCapture extends HeadlessChrome
{
    public const DIRECTORY = 'assets/sections';

    private const VIEWPORT_WIDTH = 1440;

    private const VIEWPORT_HEIGHT = 1000;

    private const MAX_HEIGHT = 1400;

    private const OUTPUT_SCALE = 0.5;

    private const SECONDS_PER_PAGE = 40;

    private const SETTLE_MS = 900;

    private const HIDE_CSS = '*,*::before,*::after{animation-duration:0s!important;animation-delay:0s!important;transition:none!important;caret-color:transparent!important}'
        .'.site-header,.lang-float,.cookie-notice,.wa-float,.case-lightbox{display:none!important}';

    public static function publicPath(string $preview): string
    {
        return '/'.self::DIRECTORY.'/'.$preview.'.webp';
    }

    public static function filePath(string $preview): string
    {
        return public_path(self::DIRECTORY.'/'.$preview.'.webp');
    }

    /**
     * @param  array<string, list<string>>  $targetsByPath  caminho público => tipos de seção (último segmento do type) a fotografar
     * @param  array<string, string>  $previewByKind  tipo de seção => nome da miniatura
     * @param  callable(string, string|null): void  $report  recebe o tipo e o erro (null quando deu certo)
     */
    public function capture(array $targetsByPath, array $previewByKind, callable $report): void
    {
        File::ensureDirectoryExists(public_path(self::DIRECTORY));
        $pending = $previewByKind;
        $base = rtrim((string) config('cms.capture_url'), '/');

        $this->withPage(
            self::VIEWPORT_WIDTH,
            self::VIEWPORT_HEIGHT,
            max(60, count($targetsByPath) * self::SECONDS_PER_PAGE),
            function (string $session) use ($targetsByPath, &$pending, $base, $report): void {
                $this->prepare($session);

                foreach ($targetsByPath as $path => $kinds) {
                    $kinds = array_values(array_filter($kinds, fn (string $kind): bool => isset($pending[$kind])));

                    if ($kinds === []) {
                        continue;
                    }

                    $this->open($session, $base.$path);

                    foreach ($kinds as $kind) {
                        try {
                            $png = $this->shoot($session, $kind);
                        } catch (RuntimeException $exception) {
                            $report($kind, $exception->getMessage());

                            continue;
                        }

                        if ($png === null) {
                            continue;
                        }

                        $this->store($png, $pending[$kind]);
                        unset($pending[$kind]);
                        $report($kind, null);
                    }
                }
            },
        );
    }

    private function prepare(string $session): void
    {
        $this->send('Page.enable', [], $session);
        $this->send('Emulation.setDeviceMetricsOverride', [
            'width' => self::VIEWPORT_WIDTH,
            'height' => self::VIEWPORT_HEIGHT,
            'deviceScaleFactor' => 1,
            'mobile' => false,
        ], $session);
        $this->send('Emulation.setEmulatedMedia', [
            'features' => [['name' => 'prefers-reduced-motion', 'value' => 'reduce']],
        ], $session);
    }

    private function open(string $session, string $url): void
    {
        $navigation = $this->send('Page.navigate', ['url' => $url], $session);

        if (! empty($navigation['errorText'])) {
            throw new RuntimeException('Não consegui abrir '.$url.': '.$navigation['errorText'].'.');
        }

        $this->waitFor('Page.loadEventFired', microtime(true) + 20);
        $until = microtime(true) + 12;

        do {
            $this->pump(microtime(true) + 0.25);
            $rendered = $this->send('Runtime.evaluate', [
                'expression' => 'Boolean(document.querySelector("main [data-section], main .hero"))',
                'returnByValue' => true,
            ], $session);
        } while (($rendered['result']['value'] ?? false) !== true && microtime(true) < $until);

        $this->send('Runtime.evaluate', [
            'expression' => 'document.head.insertAdjacentHTML("beforeend", '.json_encode('<style>'.$this->devFontsCss().self::HIDE_CSS.'</style>').');'
                .' document.querySelectorAll("img[loading=lazy]").forEach((image) => { image.loading = "eager"; });'
                .' document.fonts.ready.then(() => true)',
            'awaitPromise' => true,
        ], $session);
    }

    /**
     * Devolve o PNG da primeira seção do tipo na página aberta, ou null quando a página não tem a seção visível.
     */
    private function shoot(string $session, string $kind): ?string
    {
        $selector = json_encode($kind === 'hero' ? 'main .hero' : 'main [data-section="'.$kind.'"]');
        $located = $this->send('Runtime.evaluate', [
            'expression' => '(() => { const node = document.querySelector('.$selector.'); if (!node || node.offsetHeight < 40) return false;'
                .' node.scrollIntoView({ block: "start" });'
                .' node.querySelectorAll(".is-reveal, .plan-tier, .plan-base, .is-word-reveal, .loop").forEach((child) => child.classList.add("is-in"));'
                .' return true; })()',
            'returnByValue' => true,
        ], $session);

        if (($located['result']['value'] ?? false) !== true) {
            return null;
        }

        $until = microtime(true) + 8;

        do {
            $this->pump(microtime(true) + 0.25);
            $check = $this->send('Runtime.evaluate', [
                'expression' => '[...document.querySelector('.$selector.').querySelectorAll("img")].every((image) => image.complete)',
                'returnByValue' => true,
            ], $session);
        } while (($check['result']['value'] ?? false) !== true && microtime(true) < $until);

        $this->pump(microtime(true) + self::SETTLE_MS / 1000);

        $box = $this->send('Runtime.evaluate', [
            'expression' => '(() => { const rect = document.querySelector('.$selector.').getBoundingClientRect(); return { y: rect.top + window.scrollY, height: rect.height }; })()',
            'returnByValue' => true,
        ], $session)['result']['value'] ?? null;

        if (! is_array($box) || ($box['height'] ?? 0) < 40) {
            return null;
        }

        $shot = $this->send('Page.captureScreenshot', [
            'format' => 'png',
            'captureBeyondViewport' => true,
            'clip' => [
                'x' => 0,
                'y' => (float) $box['y'],
                'width' => self::VIEWPORT_WIDTH,
                'height' => min((float) $box['height'], self::MAX_HEIGHT),
                'scale' => self::OUTPUT_SCALE,
            ],
        ], $session);
        $bytes = base64_decode((string) ($shot['data'] ?? ''), true);

        if (! is_string($bytes) || ! str_starts_with($bytes, "\x89PNG")) {
            throw new RuntimeException('O Chrome não devolveu a imagem.');
        }

        return $bytes;
    }

    private function store(string $png, string $preview): void
    {
        $image = imagecreatefromstring($png);

        if ($image === false) {
            throw new RuntimeException('Não consegui processar a imagem.');
        }

        imagewebp($image, self::filePath($preview), 80);
    }

    protected function missingChromeMessage(): string
    {
        return 'Não achei o Chrome no servidor para fotografar as seções.';
    }

    protected function timeoutMessage(): string
    {
        return 'A captura das seções demorou demais.';
    }
}

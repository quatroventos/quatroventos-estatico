<?php

namespace App\Services\Seo;

use App\Services\Mockup\HeadlessChrome;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Gera a OG image a partir do topo da página (header e hero), em 1200x630.
 *
 * O payload (com o conteúdo ainda não salvo) fica num token de cache de vida curta; o Chrome do servidor abre a rota
 * interna /__og/{token} pelo endereço local da aplicação, sem depender de a página estar publicada.
 */
class OgImageCapture extends HeadlessChrome
{
    public const WIDTH = 1200;

    public const HEIGHT = 630;

    /**
     * A página é renderizada como um desktop comum (1440 px, mesma proporção 1,91:1) e reduzida para 1200x630.
     */
    private const VIEWPORT_WIDTH = 1440;

    private const VIEWPORT_HEIGHT = 756;

    private const SCALE = 1.5;

    private const DEADLINE_SECONDS = 45;

    private const SETTLE_MS = 2500;

    private const TOKEN_TTL_SECONDS = 300;

    private const HIDE_CSS = '.wa-float,.skip,.mobile-nav{display:none!important}*,*::before,*::after{animation-duration:0s!important;animation-delay:0s!important;transition:none!important;caret-color:transparent!important}';

    public static function tokenKey(string $token): string
    {
        return 'og-capture:'.$token;
    }

    /**
     * @param  array<string, mixed>  $payload
     * @param  array<string, mixed>  $sitePayload
     * @return string caminho público da imagem gerada (/storage/media/og/...)
     */
    public function capture(array $payload, array $sitePayload, string $name): string
    {
        $token = Str::random(40);
        Cache::put(self::tokenKey($token), ['payload' => $payload, 'site' => $sitePayload], self::TOKEN_TTL_SECONDS);

        try {
            $png = $this->withPage(
                self::VIEWPORT_WIDTH,
                self::VIEWPORT_HEIGHT,
                self::DEADLINE_SECONDS,
                fn (string $session): string => $this->shoot($session, rtrim((string) config('cms.capture_url'), '/').'/__og/'.$token),
            );
        } finally {
            Cache::forget(self::tokenKey($token));
        }

        return $this->store($png, $name);
    }

    private function shoot(string $session, string $url): string
    {
        $this->send('Page.enable', [], $session);
        $this->send('Emulation.setDeviceMetricsOverride', [
            'width' => self::VIEWPORT_WIDTH,
            'height' => self::VIEWPORT_HEIGHT,
            'deviceScaleFactor' => self::SCALE,
            'mobile' => false,
        ], $session);
        $this->send('Emulation.setEmulatedMedia', [
            'features' => [['name' => 'prefers-reduced-motion', 'value' => 'reduce']],
        ], $session);

        $navigation = $this->send('Page.navigate', ['url' => $url], $session);

        if (! empty($navigation['errorText'])) {
            throw new RuntimeException('Não consegui abrir a página para a print: '.$navigation['errorText'].'.');
        }

        $this->waitFor('Page.loadEventFired', min($this->deadline - 10, microtime(true) + 20));
        $this->send('Runtime.evaluate', [
            'expression' => 'document.head.insertAdjacentHTML("beforeend", '.json_encode('<style>'.$this->devFontsCss().self::HIDE_CSS.'</style>').'); window.scrollTo(0, 0);',
        ], $session);
        $this->pump(microtime(true) + self::SETTLE_MS / 1000);
        $this->send('Runtime.evaluate', ['expression' => 'document.fonts.ready.then(() => true)', 'awaitPromise' => true], $session);

        $ready = $this->send('Runtime.evaluate', [
            'expression' => 'Boolean(document.querySelector("#site-root") && document.querySelector("#site-root").children.length)',
            'returnByValue' => true,
        ], $session);

        if (($ready['result']['value'] ?? false) !== true) {
            throw new RuntimeException('A página não renderizou a tempo para a print.');
        }

        $shot = $this->send('Page.captureScreenshot', [
            'format' => 'png',
            'fromSurface' => true,
            'clip' => ['x' => 0, 'y' => 0, 'width' => self::VIEWPORT_WIDTH, 'height' => self::VIEWPORT_HEIGHT, 'scale' => 1],
        ], $session);
        $bytes = base64_decode((string) ($shot['data'] ?? ''), true);

        if (! is_string($bytes) || ! str_starts_with($bytes, "\x89PNG")) {
            throw new RuntimeException('O Chrome não devolveu a print.');
        }

        return $bytes;
    }

    /**
     * Com o Vite em dev, as fontes do CSS apontam para o endereço do Vite no host, fora do alcance do Chrome do container.
     * Nesse caso as @font-face do site entram embutidas na página de captura.
     */
    private function devFontsCss(): string
    {
        if (! is_file(public_path('hot'))) {
            return '';
        }

        preg_match_all('/@font-face\s*\{[^}]*font-family:\s*"([^"]+)"[^}]*url\("\.\.\/fonts\/([^"]+)"\)[^}]*font-weight:\s*([^;]+);/', (string) @file_get_contents(resource_path('css/site.css')), $faces, PREG_SET_ORDER);

        $css = '';

        foreach ($faces as [, $family, $file, $weight]) {
            $path = resource_path('fonts/'.basename($file));

            if (is_file($path)) {
                $css .= '@font-face{font-family:"'.$family.'";src:url(data:font/woff2;base64,'.base64_encode((string) file_get_contents($path)).') format("woff2");font-weight:'.trim($weight).';font-display:block}';
            }
        }

        return $css;
    }

    private function store(string $png, string $name): string
    {
        $source = imagecreatefromstring($png);

        if ($source === false) {
            throw new RuntimeException('Não consegui processar a print.');
        }

        $canvas = imagecreatetruecolor(self::WIDTH, self::HEIGHT);
        imagecopyresampled($canvas, $source, 0, 0, 0, 0, self::WIDTH, self::HEIGHT, imagesx($source), imagesy($source));

        ob_start();
        imagejpeg($canvas, null, 86);
        $jpeg = (string) ob_get_clean();

        $path = 'media/og/'.(Str::slug($name) ?: 'pagina').'-'.now()->format('YmdHis').'.jpg';
        Storage::disk('public')->put($path, $jpeg);

        return '/storage/'.$path;
    }

    protected function missingChromeMessage(): string
    {
        return 'Não achei o Chrome no servidor para gerar a OG image.';
    }

    protected function timeoutMessage(): string
    {
        return 'A página demorou demais para carregar na print.';
    }
}

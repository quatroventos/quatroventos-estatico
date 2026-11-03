<?php

namespace App\Console\Commands;

use App\Cms\SectionCatalog;
use App\Models\CaseStudy;
use App\Models\Page;
use App\Services\PublicContent;
use App\Services\SectionThumbnailCapture;
use App\Services\Seo\OgImageCapture;
use App\Support\Locales;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Fotografa a primeira ocorrência de cada seção da biblioteca para o seletor de seções do CMS.
 * Prefere conteúdo publicado; o que só existe em rascunho (ex.: o case de exemplo de componentes) entra pela rota interna de captura.
 */
class GenerateSectionThumbnails extends Command
{
    protected $signature = 'site:section-thumbnails
        {--only= : Seções separadas por vírgula (nome do preview, ex.: photo-text,results)}
        {--missing : Só as seções ainda sem miniatura}';

    protected $description = 'Gera as miniaturas das seções exibidas no seletor de seções do CMS.';

    /** @var list<string> */
    private array $tokens = [];

    public function handle(SectionThumbnailCapture $capture, PublicContent $content): int
    {
        $previewByKind = $this->wanted();

        if ($previewByKind === []) {
            $this->info('Nenhuma seção para fotografar.');

            return self::SUCCESS;
        }

        $targets = $this->targets(array_keys($previewByKind), $content);
        $absent = array_diff(array_keys($previewByKind), array_merge([], ...array_values($targets)));
        $done = [];

        try {
            $capture->capture($targets, $previewByKind, function (string $kind, ?string $error) use (&$done): void {
                if ($error === null) {
                    $done[$kind] = true;
                    $this->line('  + '.$kind);

                    return;
                }

                $this->error('  x '.$kind.': '.$error);
            });
        } catch (RuntimeException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        } finally {
            foreach ($this->tokens as $token) {
                Cache::forget(OgImageCapture::tokenKey($token));
            }
        }

        $missing = array_diff(array_keys($previewByKind), array_keys($done));

        if ($missing !== []) {
            $this->warn('Sem miniatura (nenhuma ocorrência visível): '.implode(', ', $missing));
        }

        if ($absent !== []) {
            $this->line('Fora de qualquer página ou case: '.implode(', ', $absent));
        }

        return self::SUCCESS;
    }

    /**
     * @return array<string, string> tipo de seção => nome do preview
     */
    private function wanted(): array
    {
        $only = array_filter(array_map('trim', explode(',', (string) $this->option('only'))));
        $wanted = [];

        foreach (SectionCatalog::library()[0]['items'] as $item) {
            $preview = $item['preview'];

            if ($only !== [] && ! in_array($preview, $only, true)) {
                continue;
            }

            if ($this->option('missing') && is_file(SectionThumbnailCapture::filePath($preview))) {
                continue;
            }

            $wanted[Str::afterLast($item['type'], '.')] = $preview;
        }

        return $wanted;
    }

    /**
     * URLs no idioma padrão que contêm alguma das seções: páginas e cases publicados, depois rascunhos por token.
     *
     * @param  list<string>  $kinds
     * @return array<string, list<string>>
     */
    private function targets(array $kinds, PublicContent $content): array
    {
        $targets = [];
        $published = [
            ...Page::query()->published()->where('locale', Locales::default())->orderBy('slug')->get()->all(),
            ...CaseStudy::query()->published()->where('locale', Locales::default())->orderBy('slug')->get()->all(),
        ];

        foreach ($published as $model) {
            if (($found = $this->kindsIn($model->sections(), $kinds)) !== []) {
                $targets[$model->publicPath()] = $found;
            }
        }

        $covered = array_merge([], ...array_values($targets));
        $drafts = [
            ...Page::query()->where('locale', Locales::default())->whereNotIn('id', collect($published)->whereInstanceOf(Page::class)->pluck('id'))->orderBy('slug')->get()->all(),
            ...CaseStudy::query()->where('locale', Locales::default())->whereNotIn('id', collect($published)->whereInstanceOf(CaseStudy::class)->pluck('id'))->orderBy('slug')->get()->all(),
        ];

        foreach ($drafts as $model) {
            $found = array_values(array_diff($this->kindsIn($model->sections(), $kinds), $covered));

            if ($found === []) {
                continue;
            }

            $token = Str::random(40);
            Cache::put(OgImageCapture::tokenKey($token), [
                'payload' => $model instanceof Page ? $content->page($model) : $content->caseStudy($model),
                'site' => $content->site($model->localeCode()),
            ], now()->addMinutes(30));
            $this->tokens[] = $token;
            $targets['/__og/'.$token] = $found;
            $covered = [...$covered, ...$found];
        }

        return $targets;
    }

    /**
     * @param  list<array<string, mixed>>  $sections
     * @param  list<string>  $kinds
     * @return list<string>
     */
    private function kindsIn(array $sections, array $kinds): array
    {
        $found = [];

        foreach ($sections as $section) {
            $kind = Str::afterLast((string) ($section['type'] ?? ''), '.');

            if (in_array($kind, $kinds, true)) {
                $found[$kind] = true;
            }
        }

        return array_keys($found);
    }
}

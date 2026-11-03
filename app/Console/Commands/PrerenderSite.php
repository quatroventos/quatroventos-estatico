<?php

namespace App\Console\Commands;

use App\Models\CaseStudy;
use App\Models\Page;
use App\Services\PublicContent;
use App\Services\SitePrerenderer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use RuntimeException;

class PrerenderSite extends Command
{
    protected $signature = 'site:prerender';

    protected $description = 'Gera o HTML público de cada URL publicada para o Blade servir sem React.';

    public function handle(PublicContent $content, SitePrerenderer $prerenderer): int
    {
        $payloads = [];

        foreach (Page::query()->published()->orderBy('locale')->orderBy('slug')->get() as $page) {
            $payloads[] = [
                'page' => $content->page($page),
                'site' => $content->site($page->localeCode()),
            ];
        }

        foreach (CaseStudy::query()->published()->orderBy('locale')->orderBy('slug')->get() as $case) {
            $payloads[] = [
                'page' => $content->caseStudy($case),
                'site' => $content->site($case->localeCode()),
            ];
        }

        if ($payloads === []) {
            $this->error('Nenhuma URL publicada.');

            return self::FAILURE;
        }

        try {
            $prerenderer->render($payloads, output: function (string $type, string $buffer): void {
                $this->output->write($buffer);
            });
        } catch (RuntimeException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->pruneStale($payloads);

        return self::SUCCESS;
    }

    /**
     * Remove o HTML de URLs que deixaram de existir (slug renomeado, página despublicada ou apagada).
     *
     * @param  list<array{page: array<string, mixed>, site: mixed}>  $payloads
     */
    private function pruneStale(array $payloads): void
    {
        $root = storage_path('app/prerender');
        $expected = [];

        foreach ($payloads as $payload) {
            $expected[SitePrerenderer::fileFor($payload['page'])] = true;
        }

        $removed = 0;

        foreach (File::allFiles($root) as $file) {
            if ($file->getExtension() === 'html' && ! isset($expected[$file->getPathname()])) {
                File::delete($file->getPathname());
                $removed++;
            }
        }

        if ($removed > 0) {
            $this->line($removed.' arquivo(s) antigo(s) removido(s)');
        }
    }
}

<?php

namespace App\Services;

use App\Models\AiVisibilityReport;
use App\Models\CaseStudy;
use App\Models\Page;
use App\Support\Locales;
use Illuminate\Support\Collection;

class SitemapService
{
    public function baseUrl(): string
    {
        return rtrim((string) config('app.url'), '/');
    }

    public function sitemapUrl(): string
    {
        return $this->baseUrl().'/sitemap.xml';
    }

    public function robotsUrl(): string
    {
        return $this->baseUrl().'/robots.txt';
    }

    public function llmsUrl(): string
    {
        return $this->baseUrl().'/llms.txt';
    }

    /**
     * Caminhos do painel e de rotas administrativas. Vale para cada user-agent:
     * um grupo específico não herda o Disallow do `*`.
     *
     * @return list<string>
     */
    public function adminDisallowPaths(): array
    {
        return array_values(array_unique([
            '/'.cms_admin_path(),
            '/gestao',
            '/admin',
            '/login',
            '/filament',
            '/livewire',
        ]));
    }

    /**
     * Texto servido em /robots.txt: o editado no painel ou, sem edição, o gerado.
     */
    public function robots(): string
    {
        $custom = app(SystemOptions::class)->site()?->robots_txt;

        return filled($custom) ? rtrim((string) $custom)."\n" : $this->robotsAuto();
    }

    /**
     * Texto servido em /llms.txt e /llm.txt: o editado no painel ou, sem edição, o gerado.
     */
    public function llms(): string
    {
        $custom = app(SystemOptions::class)->site()?->llms_txt;

        return filled($custom) ? rtrim((string) $custom)."\n" : $this->llmsAuto();
    }

    public function robotsAuto(): string
    {
        $rules = "Allow: /\n";
        foreach ($this->adminDisallowPaths() as $path) {
            $rules .= 'Disallow: '.$path."\n";
        }

        $agents = [
            '*',
            'GPTBot',
            'OAI-SearchBot',
            'ChatGPT-User',
            'ClaudeBot',
            'PerplexityBot',
            'Google-Extended',
            'Applebot-Extended',
        ];

        $body = '';
        foreach ($agents as $agent) {
            $body .= "User-agent: {$agent}\n{$rules}\n";
        }

        return $body
            ."# {$this->llmsUrl()}\n"
            ."# {$this->baseUrl()}/llm.txt\n"
            ."Sitemap: {$this->sitemapUrl()}\n";
    }

    public function llmsAuto(): string
    {
        $site = app(SystemOptions::class)->site();
        $name = filled($site?->name) ? (string) $site->name : 'Quatro Ventos';
        $summary = $this->plainLine((string) ($site?->seo_description ?? ''));

        $lines = ["# {$name}", ''];
        if ($summary !== '') {
            $lines[] = '> '.$summary;
            $lines[] = '';
        }

        $lines[] = '## Ferramentas';
        $lines[] = '';
        $lines[] = '- [Como aparecer no ChatGPT?]('.$this->absolute('/'.AiVisibilityReport::PATH).'): Checklist e nota gratuita de prontidão para ChatGPT, Claude e Perplexity.';
        $lines[] = '';

        $pages = Page::query()->published()->get()
            ->reject(fn (Page $page) => str_contains(strtolower((string) $page->robots), 'noindex'))
            ->groupBy(fn (Page $page) => Locales::normalize($page->localeCode()));

        foreach (Locales::all() as $locale) {
            $group = $pages->get($locale, collect());
            if ($group->isEmpty()) {
                continue;
            }

            $heading = (string) (Locales::meta($locale)['name'] ?? $locale);
            $lines[] = '## '.$heading;
            $lines[] = '';

            foreach ($group->sortBy(fn (Page $page) => [$page->isHome() ? 0 : 1, $page->slug])->values() as $page) {
                $title = $this->plainLine((string) ($page->seo_title ?: $page->slug));
                $title = str_replace(['[', ']'], '', $title);
                $description = $this->plainLine((string) $page->seo_description);
                $url = $this->absolute($page->publicPath());
                $lines[] = $description === ''
                    ? "- [{$title}]({$url})"
                    : "- [{$title}]({$url}): {$description}";
            }

            $lines[] = '';
        }

        return rtrim(implode("\n", $lines))."\n";
    }

    private function plainLine(string $value): string
    {
        $value = trim(preg_replace('/\s+/u', ' ', $value) ?? '');

        return $value;
    }

    /** @return list<array{source: string, slug: ?string, locale: ?string, path: string, loc: string, lastmod: ?string, alternates: array<string, string>}> */
    public function entries(): array
    {
        $pages = $this->localized(
            Page::query()->published()->get()
                ->reject(fn (Page $page) => str_contains(strtolower((string) $page->robots), 'noindex'))
                ->reject(fn (Page $page) => $page->slug === AiVisibilityReport::PATH),
            'page'
        );

        $cases = $this->localized(CaseStudy::query()->published()->get(), 'case');

        $extra = collect(app(SystemOptions::class)->site()?->sitemap_extra ?? [])
            ->map(fn ($line) => trim((string) $line))
            ->filter()
            ->unique()
            ->values()
            ->map(fn (string $path) => [
                'source' => 'extra',
                'slug' => null,
                'locale' => null,
                'path' => $this->normalizePath($path),
                'loc' => $this->absolute($path),
                'lastmod' => now()->toAtomString(),
                'alternates' => [],
            ]);

        $tools = collect([[
            'source' => 'tool',
            'slug' => AiVisibilityReport::PATH,
            'locale' => Locales::default(),
            'path' => '/'.AiVisibilityReport::PATH,
            'loc' => $this->absolute('/'.AiVisibilityReport::PATH),
            'lastmod' => now()->toAtomString(),
            'alternates' => [],
        ]]);

        return $tools->concat($pages)->concat($cases)->concat($extra)->values()->all();
    }

    /**
     * @param  Collection<int, Page|CaseStudy>  $rows
     * @return Collection<int, array<string, mixed>>
     */
    private function localized(Collection $rows, string $source): Collection
    {
        $groups = $rows->groupBy(fn ($row) => (int) ($row->translation_of ?? $row->id));

        return $rows
            ->sortBy(fn ($row) => [array_search(Locales::normalize($row->locale), Locales::all(), true), $row->slug])
            ->values()
            ->map(function ($row) use ($groups, $source) {
                $siblings = $groups->get((int) ($row->translation_of ?? $row->id), collect());
                $alternates = $siblings->count() > 1
                    ? $siblings
                        ->sortBy(fn ($item) => array_search(Locales::normalize($item->locale), Locales::all(), true))
                        ->mapWithKeys(fn ($item) => [Locales::normalize($item->locale) => $this->absolute($item->publicPath())])
                        ->all()
                    : [];

                return [
                    'source' => $source,
                    'slug' => $row->slug,
                    'locale' => Locales::normalize($row->locale),
                    'path' => $row->publicPath(),
                    'loc' => $this->absolute($row->publicPath()),
                    'lastmod' => $row->updated_at?->toAtomString(),
                    'alternates' => $alternates,
                ];
            });
    }

    public function xml(): string
    {
        $urls = collect($this->entries())->map(function (array $entry) {
            $lastmod = $entry['lastmod']
                ? '<lastmod>'.e($entry['lastmod']).'</lastmod>'
                : '';

            $links = '';
            $alternates = $entry['alternates'] ?? [];
            foreach ($alternates as $locale => $href) {
                $links .= '<xhtml:link rel="alternate" hreflang="'.e(Locales::hreflang($locale)).'" href="'.e($href).'"/>';
            }
            if ($alternates !== [] && isset($alternates[Locales::default()])) {
                $links .= '<xhtml:link rel="alternate" hreflang="x-default" href="'.e($alternates[Locales::default()]).'"/>';
            }

            return '<url><loc>'.e($entry['loc']).'</loc>'.$lastmod.$links.'</url>';
        })->implode('');

        return '<?xml version="1.0" encoding="UTF-8"?>'
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">'
            .$urls
            .'</urlset>';
    }

    public function absolute(string $path): string
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return $this->baseUrl().$this->normalizePath($path);
    }

    private function normalizePath(string $path): string
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            $parsed = parse_url($path, PHP_URL_PATH);

            return $parsed ?: '/';
        }

        return '/'.ltrim($path, '/');
    }
}

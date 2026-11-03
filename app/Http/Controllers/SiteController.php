<?php

namespace App\Http\Controllers;

use App\Jobs\PrerenderSiteJob;
use App\Models\CaseStudy;
use App\Models\Page;
use App\Services\PublicContent;
use App\Services\SitemapService;
use App\Services\SitePrerenderer;
use App\Services\SystemOptions;
use App\Support\Locales;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SiteController extends Controller
{
    public function home(): View
    {
        $page = Page::query()->published()->forLocale(Locales::current())->where('template', 'home')->firstOrFail();

        return $this->render($page);
    }

    public function show(string $slug): View|RedirectResponse
    {
        $locale = Locales::current();
        $page = Page::query()->published()->forLocale($locale)->where('slug', $slug)->first();

        if ($page === null) {
            return $this->legacyRedirect($locale, $slug) ?? abort(404);
        }

        if ($page->isHome()) {
            abort(404);
        }

        return $this->render($page);
    }

    public function legacy(string $slug): RedirectResponse
    {
        return $this->legacyRedirect(Locales::current(), $slug) ?? abort(404);
    }

    private function legacyRedirect(string $locale, string $slug): ?RedirectResponse
    {
        if (Locales::isDefault($locale)) {
            return null;
        }

        $source = config('locale_redirects.'.$locale.'.'.$slug);
        if (! is_string($source)) {
            return null;
        }

        $original = Page::query()->originals()->published()
            ->where($source === 'home' ? 'template' : 'slug', $source)
            ->first();
        $translation = $original?->translationFor($locale);

        $target = $translation !== null && $translation->status === 'published'
            ? $translation->publicPath()
            : Locales::path($locale, '/');

        return redirect($target, 301);
    }

    public function caseStudy(string $slug, PublicContent $content): View
    {
        $case = CaseStudy::query()->published()->forLocale(Locales::current())->where('slug', $slug)->firstOrFail();

        $payload = $content->caseStudy($case);
        $sitePayload = $content->site($case->localeCode());

        return view('site.app', [
            'site' => app(SystemOptions::class)->site(),
            'payload' => $payload,
            'sitePayload' => $sitePayload,
            'fragment' => $this->fragmentFor($payload, $sitePayload),
            'cmsPreview' => false,
        ]);
    }

    public function sitemap(SitemapService $sitemap): Response
    {
        return response($sitemap->xml(), 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    public function revisao(): Response
    {
        abort_unless(app()->environment('local'), 404);

        return response()
            ->view('site.revisao')
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }

    public function robots(SitemapService $sitemap): Response
    {
        return response($sitemap->robots(), 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function llms(SitemapService $sitemap): Response
    {
        return response($sitemap->llms(), 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    public function legacyPhp(string $legacy): RedirectResponse
    {
        $path = '/'.trim($legacy, '/');
        $map = config('redirects');
        $target = is_array($map) ? ($map[$path] ?? null) : null;

        if (! is_string($target)) {
            if ($path === '/index') {
                $target = '/';
            } elseif (Page::query()->published()->originals()->where('slug', trim($path, '/'))->exists()) {
                $target = $path;
            }
        }

        return is_string($target) ? redirect($target, 301) : abort(404);
    }

    private function render(Page $page, bool $cmsPreview = false): View
    {
        $content = app(PublicContent::class);

        $payload = $content->page($page);
        $sitePayload = $content->site($page->localeCode());

        return view('site.app', [
            'page' => $page,
            'site' => app(SystemOptions::class)->site(),
            'payload' => $payload,
            'sitePayload' => $sitePayload,
            'fragment' => $cmsPreview ? '' : $this->fragmentFor($payload, $sitePayload),
            'cmsPreview' => $cmsPreview,
        ]);
    }

    /**
     * HTML pré-gerado da URL. Se ainda não existir (página recém-publicada antes da fila rodar,
     * ou geração que falhou), gera só esta URL na hora e agenda a geração completa.
     *
     * @param  array<string, mixed>  $payload
     */
    private function fragmentFor(array $payload, mixed $sitePayload): string
    {
        $file = SitePrerenderer::fileFor($payload);

        if (is_file($file)) {
            return File::get($file);
        }

        try {
            Cache::lock('prerender:'.md5($file), 60)->block(45, function () use ($file, $payload, $sitePayload): void {
                if (! is_file($file)) {
                    app(SitePrerenderer::class)->render([['page' => $payload, 'site' => $sitePayload]], 45);
                }
            });
        } catch (Throwable $exception) {
            Log::error('Falha ao gerar o HTML público sob demanda.', ['file' => $file, 'error' => $exception->getMessage()]);
        }

        PrerenderSiteJob::schedule();

        if (is_file($file)) {
            return File::get($file);
        }

        abort(503, '', ['Retry-After' => '10']);
    }
}

<?php

namespace Tests\Feature;

use App\Cms\EnsureAiVisibilityCmsPage;
use App\Models\AiVisibilityReport;
use App\Models\Page;
use App\Services\Seo\AiVisibilityAnalyzer;
use App\Services\SitePrerenderer;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiVisibilityToolTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['cms.og_on_save' => false]);
        EnsureAiVisibilityCmsPage::apply(force: true);
    }

    public function test_landing_page_is_served_from_cms_with_editable_seo(): void
    {
        $page = Page::query()
            ->where('slug', AiVisibilityReport::PATH)
            ->firstOrFail();

        $this->assertSame(
            'Como aparecer no ChatGPT? Teste gratuito do seu site | Quatro Ventos',
            $page->seo_title
        );

        $file = SitePrerenderer::fileFor([
            'locale' => 'pt-BR',
            'path' => '/'.AiVisibilityReport::PATH,
        ]);
        File::ensureDirectoryExists(dirname($file));
        $this->assertSame('site.ai-vis-hero', $page->content['sections'][0]['type'] ?? null);
        $this->assertTrue(collect($page->content['sections'] ?? [])->contains(fn (array $section): bool => ($section['type'] ?? '') === 'site.ai-vis-measure'));
        $measure = collect($page->content['sections'] ?? [])->first(fn (array $section): bool => ($section['type'] ?? '') === 'site.ai-vis-measure');
        $this->assertCount(15, $measure['data']['items'] ?? []);

        File::put($file, <<<'HTML'
<div class="ai-vis">
<main id="conteudo">
  <section class="section ai-vis-hero is-mark-draw">
    <div class="ai-vis-atmosphere" aria-hidden="true"><div class="agent-log"></div></div>
    <div class="wrap">
      <div class="ai-vis-hero-grid">
        <h1>Como aparecer no <em class="mark">ChatGPT</em>?</h1>
        <form class="ai-vis-form" action="/api/como-aparecer-no-chatgpt" method="post">
          <input id="ai-vis-url" name="url" type="text" />
          <button type="submit">Ver minha nota</button>
        </form>
        <p class="hint" id="ai-vis-status" role="status" aria-live="polite" hidden></p>
      </div>
    </div>
  </section>
  <section class="ai-vis-pillars"><h2>Três passos simples</h2></section>
  <section class="ai-vis-measure-section"><h2>O que olhamos no teste</h2></section>
  <article class="ai-vis-article"><h2>Como aparecer no ChatGPT, na prática</h2></article>
  <p>Isso é a mesma coisa que aparecer no Google?</p>
</main>
</div>
HTML);

        try {
            $html = $this->get('/'.AiVisibilityReport::PATH)
                ->assertOk()
                ->assertSee('Como aparecer no ChatGPT? Teste gratuito do seu site | Quatro Ventos', false)
                ->assertSee('ai-vis-hero', false)
                ->assertSee('ai-vis-form', false)
                ->assertSee('ai-vis-measure-section', false)
                ->assertSee('ai-vis-article', false)
                ->assertSee('name="author"', false)
                ->assertDontSee('id="site-root"', false)
                ->getContent();

            $this->assertStringContainsString('Como aparecer no ChatGPT', $html);
            $this->assertStringContainsString('Isso é a mesma coisa que aparecer no Google?', $html);
            $this->assertStringContainsString('aria-live="polite"', $html);
            $this->assertStringNotContainsString('a gente', $html);
        } finally {
            File::delete($file);
        }

        $page->seo_title = 'Título CMS editado | Quatro Ventos';
        $page->seo_description = 'Description editada no CMS.';
        $page->save();

        File::put($file, '<main id="conteudo"><h1>Como aparecer no ChatGPT?</h1></main>');

        try {
            $this->get('/'.AiVisibilityReport::PATH)
                ->assertOk()
                ->assertSee('<title>Título CMS editado | Quatro Ventos</title>', false)
                ->assertSee('Description editada no CMS.', false);
        } finally {
            File::delete($file);
        }
    }

    public function test_legacy_path_redirects_to_seo_slug(): void
    {
        $this->get('/'.AiVisibilityReport::LEGACY_PATH)
            ->assertRedirect('/'.AiVisibilityReport::PATH);
    }

    public function test_analyze_stores_shareable_report(): void
    {
        Http::fake(function (Request $request) {
            return $this->fakeSiteResponse($request);
        });

        $response = $this->postJson('/api/'.AiVisibilityReport::PATH, ['url' => 'exemplo.com.br'])
            ->assertOk()
            ->assertJsonStructure(['id', 'path', 'score', 'band']);

        $id = $response->json('id');
        $this->assertDatabaseHas('ai_visibility_reports', ['public_id' => $id, 'host' => 'exemplo.com.br']);

        $report = AiVisibilityReport::query()->where('public_id', $id)->firstOrFail();
        $checkIds = collect($report->checks)->pluck('id')->all();
        $this->assertSame(
            ['robots', 'crawler', 'readable', 'markdown', 'not_found', 'redirect', 'auth', 'answers', 'schema', 'meta', 'org', 'llms', 'trust', 'sitemap', 'structure'],
            $checkIds
        );
        $this->assertSame(100, array_sum(array_column($report->checks, 'max')));
        $this->assertCount(15, $checkIds);

        $this->get('/'.AiVisibilityReport::PATH.'/'.$id)
            ->assertOk()
            ->assertSee('exemplo.com.br', false)
            ->assertSee('O que encontramos', false);
    }

    public function test_analyze_form_post_redirects_to_report(): void
    {
        Http::fake(function (Request $request) {
            return $this->fakeSiteResponse($request);
        });

        $response = $this->post('/api/'.AiVisibilityReport::PATH, ['url' => 'exemplo.com.br'])
            ->assertRedirect();

        $report = AiVisibilityReport::query()->latest('id')->first();
        $this->assertNotNull($report);
        $response->assertRedirect($report->publicPath());
    }

    public function test_analyzer_scores_markdown_and_404_probes(): void
    {
        Http::fake(function (Request $request) {
            return $this->fakeSiteResponse($request, markdown: true, notFoundMarkdown: true);
        });

        $report = app(AiVisibilityAnalyzer::class)->analyze('exemplo.com.br');
        $byId = collect($report['checks'])->keyBy('id');

        $this->assertSame('good', $byId['markdown']['status']);
        $this->assertSame('good', $byId['not_found']['status']);
        $this->assertSame('good', $byId['crawler']['status']);
        $this->assertSame('good', $byId['redirect']['status']);
        $this->assertSame('good', $byId['auth']['status']);
        $this->assertSame('good', $byId['structure']['status']);
        $this->assertSame('good', $byId['llms']['status']);
        $this->assertSame('good', $byId['trust']['status']);
        $this->assertSame('good', $byId['org']['status']);
        $this->assertGreaterThanOrEqual(90, $report['score']);
    }

    public function test_private_hosts_are_rejected(): void
    {
        $this->expectException(\RuntimeException::class);
        app(AiVisibilityAnalyzer::class)->normalizeUrl('http://127.0.0.1/');
    }

    public function test_sitemap_and_llms_list_the_tool(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('/'.AiVisibilityReport::PATH, false);

        $this->get('/llms.txt')
            ->assertOk()
            ->assertSee('Como aparecer no ChatGPT?', false);
    }

    /**
     * @return PromiseInterface|Response
     */
    private function fakeSiteResponse(Request $request, bool $markdown = false, bool $notFoundMarkdown = false)
    {
        $url = $request->url();
        $acceptHeader = $request->header('Accept');
        $accept = strtolower(is_array($acceptHeader) ? implode(',', $acceptHeader) : (string) $acceptHeader);

        if (str_contains($url, 'robots.txt')) {
            return Http::response("User-agent: *\nAllow: /\nSitemap: https://exemplo.com.br/sitemap.xml\n", 200);
        }
        if (str_contains($url, 'sitemap')) {
            return Http::response('<?xml version="1.0"?><urlset></urlset>', 200);
        }
        if (str_contains($url, 'llms.txt') || str_contains($url, 'llm.txt')) {
            return Http::response("# Agenda online\n\nPara clínicas.\n\n## Quando usar\n\nIdeal para clínicas que precisam de agenda online.\n\n- [Home](https://exemplo.com.br/)\n", 200);
        }
        if (str_contains($url, '__qv-404-probe-')) {
            if ($notFoundMarkdown) {
                return Http::response("# Página não encontrada\n\nVeja o [sitemap](https://exemplo.com.br/sitemap.xml) ou o [llms.txt](https://exemplo.com.br/llms.txt).\n", 404, [
                    'Content-Type' => 'text/markdown; charset=utf-8',
                ]);
            }

            return Http::response('<html><body><h1>Não encontrado</h1><p>Veja o <a href="/sitemap.xml">sitemap</a>.</p></body></html>', 404);
        }

        if ($markdown && str_contains($accept, 'text/markdown')) {
            return Http::response("# Agenda online para clínicas\n\nFeito para clínicas. Planos a partir de R$ 49 por mês.\n", 200, [
                'Content-Type' => 'text/markdown; charset=utf-8',
                'Vary' => 'Accept',
            ]);
        }

        $paragraph = str_repeat('Feito para clínicas e profissionais de saúde. Planos a partir de R$ 49 por mês. ', 8);

        $body = <<<HTML
<!doctype html><html lang="pt-BR"><head>
<title>Agenda online para clínicas</title>
<meta name="description" content="Agenda online feita para clínicas e profissionais de saúde com planos a partir de R$ 49.">
<link rel="canonical" href="https://exemplo.com.br/">
<meta property="og:image" content="https://exemplo.com.br/og.jpg">
<meta property="og:type" content="website">
<script type="application/ld+json">{"@context":"https://schema.org","@type":["SoftwareApplication","Organization"],"name":"Exemplo","description":"Agenda online","url":"https://exemplo.com.br/","offers":{"@type":"Offer","price":"49.00","priceCurrency":"BRL"},"contactPoint":{"@type":"ContactPoint","contactType":"sales","email":"oi@exemplo.com.br","telephone":"+55-11-99999-9999"},"address":{"@type":"PostalAddress","addressLocality":"São Paulo","addressCountry":"BR"}}</script>
</head><body><main>
<h1>Agenda online para clínicas</h1>
<p>{$paragraph}</p>
<a href="/sobre">Sobre</a>
<a href="/contato">Contato</a>
<a href="/privacidade">Privacidade</a>
</main></body></html>
HTML;

        return Http::response($body, 200);
    }
}

<?php

namespace Tests\Feature;

use App\Cms\EnsureAiVisibilityCmsPage;
use App\Models\AiVisibilityReport;
use App\Models\Page;
use App\Services\Seo\AiVisibilityAnalyzer;
use App\Services\SitePrerenderer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
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

        File::put($file, <<<'HTML'
<div class="ai-vis">
<main id="conteudo">
  <section class="section ai-vis-hero is-mark-draw">
    <div class="ai-vis-atmosphere" aria-hidden="true"><div class="agent-log"></div></div>
    <div class="wrap ai-vis-hero-grid">
      <h1>Como aparecer no <em class="mark">ChatGPT</em>?</h1>
      <form class="ai-vis-form" action="/api/como-aparecer-no-chatgpt" method="post">
        <input id="ai-vis-url" name="url" type="text" />
        <button type="submit">Ver minha nota</button>
      </form>
      <p class="hint" id="ai-vis-status" role="status" aria-live="polite" hidden></p>
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
            $url = $request->url();
            if (str_contains($url, 'robots.txt')) {
                return Http::response("User-agent: *\nAllow: /\n", 200);
            }
            if (str_contains($url, 'sitemap')) {
                return Http::response('<?xml version="1.0"?><urlset></urlset>', 200);
            }
            if (str_contains($url, 'llms.txt')) {
                return Http::response('Quatro Ventos', 200);
            }

            $body = <<<'HTML'
<!doctype html><html><head>
<title>Agenda online</title>
<meta name="description" content="Para clínicas">
<script type="application/ld+json">{"@context":"https://schema.org","@type":"SoftwareApplication","name":"Exemplo","offers":{"@type":"Offer","price":"49.00","priceCurrency":"BRL"}}</script>
</head><body><main>
<h1>Agenda online para clínicas</h1>
<p>Feito para clínicas e profissionais de saúde. Planos a partir de R$ 49 por mês.</p>
<a href="/sobre">Sobre</a>
<p>texto</p>
</main></body></html>
HTML;

            return Http::response($body, 200);
        });

        $response = $this->postJson('/api/'.AiVisibilityReport::PATH, ['url' => 'exemplo.com.br'])
            ->assertOk()
            ->assertJsonStructure(['id', 'path', 'score', 'band']);

        $id = $response->json('id');
        $this->assertDatabaseHas('ai_visibility_reports', ['public_id' => $id, 'host' => 'exemplo.com.br']);

        $this->get('/'.AiVisibilityReport::PATH.'/'.$id)
            ->assertOk()
            ->assertSee('exemplo.com.br', false)
            ->assertSee('O que encontramos', false);
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
}

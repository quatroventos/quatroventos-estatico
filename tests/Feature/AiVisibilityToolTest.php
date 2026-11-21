<?php

namespace Tests\Feature;

use App\Models\AiVisibilityReport;
use App\Services\Seo\AiVisibilityAnalyzer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiVisibilityToolTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_is_indexable_with_structured_data_and_faq(): void
    {
        $html = $this->get('/'.AiVisibilityReport::PATH)
            ->assertOk()
            ->assertSee('Como aparecer no', false)
            ->assertSee('ChatGPT', false)
            ->assertSee('O que olhamos no teste', false)
            ->assertSee('Três passos simples', false)
            ->assertSee('Como aparecer no', false)
            ->assertSee('na prática', false)
            ->assertSee('application/ld+json', false)
            ->assertSee('WebApplication', false)
            ->assertSee('Article', false)
            ->assertSee('HowTo', false)
            ->assertSee('FAQPage', false)
            ->assertSee('name="author"', false)
            ->assertDontSee('Aplicar no meu site', false)
            ->getContent();

        $this->assertStringContainsString('Como aparecer no ChatGPT?', $html);
        $this->assertStringContainsString('Isso é a mesma coisa que aparecer no Google?', $html);
        $this->assertStringContainsString('aria-live="polite"', $html);
        $this->assertStringNotContainsString('a gente', $html);
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

            if (str_ends_with($url, '/robots.txt')) {
                return Http::response("User-agent: *\nAllow: /\nSitemap: https://exemplo.com.br/sitemap.xml\n", 200);
            }
            if (str_contains($url, 'llms.txt') || str_contains($url, 'llm.txt')) {
                return Http::response("# Exemplo\n\n> Software para clínicas.\n\n- [Home](https://exemplo.com.br/)\n- [Sobre](https://exemplo.com.br/sobre)\n- [Preços](https://exemplo.com.br/precos)\n", 200);
            }
            if (str_contains($url, 'sitemap')) {
                return Http::response('<?xml version="1.0"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><url><loc>https://exemplo.com.br/</loc></url></urlset>', 200);
            }

            $body = str_repeat('Texto de apoio para a home ficar longa o bastante para a IA citar trechos com segurança. ', 40);

            return Http::response(<<<HTML
<!doctype html><html><head>
<title>Agenda online para clínicas | Exemplo</title>
<meta name="description" content="Agenda online para clínicas e consultórios. Planos a partir de R\$ 49 por mês." />
<meta name="author" content="Exemplo" />
<meta property="article:modified_time" content="2026-11-01T12:00:00-03:00" />
<script type="application/ld+json">{"@context":"https://schema.org","@type":"SoftwareApplication","name":"Exemplo","offers":{"@type":"Offer","price":"49.00","priceCurrency":"BRL"}}</script>
</head><body><main>
<h1>Agenda online para clínicas</h1>
<p>Feito para clínicas e profissionais de saúde. Planos a partir de R\$ 49 por mês.</p>
<a href="/sobre">Sobre</a>
<p>{$body}</p>
</main></body></html>
HTML, 200);
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

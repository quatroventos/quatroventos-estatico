<?php

namespace Tests\Feature;

use App\Models\MenuItem;
use App\Models\Page;
use App\Models\SiteSetting;
use App\Services\PublicContent;
use App\Services\Seo\AiReadiness;
use App\Services\Seo\StructuredData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class StructuredDataTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        SiteSetting::query()->create([
            'name' => 'Quatro Ventos',
            'phone' => '(41) 99999-0000',
            'email' => 'contato@quatroventos.com.br',
            'address' => 'Curitiba - PR',
            'instagram_url' => 'https://instagram.com/quatroventos',
            'seo_description' => 'Software, IA e sites sob medida.',
            'og_site_name' => 'Quatro Ventos',
            'seo_separator' => '|',
            'seo_title_template' => '%page% %sep% %site%',
        ]);
    }

    public function test_every_page_describes_the_company_and_its_update_date(): void
    {
        $home = $this->page('home', 'Quatro Ventos', [], ['template' => 'home']);

        $graph = $this->graph($home);

        $organization = $graph->firstWhere('@type', 'ProfessionalService');
        $this->assertSame('(41) 99999-0000', $organization['telephone']);
        $this->assertSame(['@type' => 'PostalAddress', 'addressLocality' => 'Curitiba', 'addressRegion' => 'PR', 'addressCountry' => 'BR'], $organization['address']);
        $this->assertSame(['https://instagram.com/quatroventos'], $organization['sameAs']);
        $this->assertSame($home->updated_at->toIso8601String(), $graph->firstWhere('@type', 'WebPage')['dateModified']);
        $this->assertNull($graph->firstWhere('@type', 'BreadcrumbList'));
    }

    public function test_home_lists_menu_categories_as_services(): void
    {
        $home = $this->page('home', 'Quatro Ventos', [], ['template' => 'home']);
        $hub = MenuItem::query()->create(['locale' => 'pt-BR', 'group' => 'header', 'label' => 'Web', 'href' => '/desenvolvimento-web', 'sort' => 1]);
        MenuItem::query()->create(['locale' => 'pt-BR', 'group' => 'header', 'label' => 'Sites', 'href' => '/criacao-de-sites', 'parent_id' => $hub->id, 'sort' => 1]);
        MenuItem::query()->create(['locale' => 'pt-BR', 'group' => 'header', 'label' => 'Contato', 'href' => '/contato', 'sort' => 2]);

        $services = $this->graph($home)->where('@type', 'Service')->values();

        $this->assertCount(1, $services);
        $this->assertSame('Web', $services[0]['name']);
        $this->assertSame('Sites', $services[0]['hasOfferCatalog']['itemListElement'][0]['itemOffered']['name']);
        $this->assertSame(url('/criacao-de-sites'), $services[0]['hasOfferCatalog']['itemListElement'][0]['itemOffered']['url']);
    }

    public function test_html_has_one_json_ld_block_per_entity_and_update_metadata(): void
    {
        $home = $this->page('home', 'Quatro Ventos', [], ['template' => 'home']);

        $html = $this->get('/')->assertOk()->getContent();

        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);
        $types = array_map(fn (string $json) => json_decode($json, true)['@type'] ?? null, $matches[1]);
        $this->assertSame(['ProfessionalService', 'WebSite', 'WebPage'], $types);
        $this->assertStringContainsString('<meta name="author" content="Quatro Ventos" />', $html);
        $this->assertStringContainsString('<meta property="article:modified_time" content="'.$home->updated_at->toIso8601String().'" />', $html);
    }

    public function test_service_page_in_header_menu_gets_service_offers_and_faq(): void
    {
        $page = $this->page('hospedagem', 'Hospedagem de sites | Quatro Ventos', [
            ['id' => 'p', 'type' => 'site.plans', 'data' => ['items' => [
                ['name' => 'Básico', 'price' => 'R$ 1.290'],
                ['name' => 'Sob consulta', 'price' => 'Fale com a gente'],
            ]]],
            ['id' => 'f', 'type' => 'site.faq', 'data' => ['items' => [
                ['question' => 'Quanto custa?', 'answer' => '<p>Depende do <strong>escopo</strong>.</p>'],
            ]]],
        ]);
        $hub = MenuItem::query()->create(['locale' => 'pt-BR', 'group' => 'header', 'label' => 'Web', 'href' => '/sites', 'sort' => 1]);
        MenuItem::query()->create(['locale' => 'pt-BR', 'group' => 'header', 'label' => 'Hospedagem', 'href' => '/hospedagem', 'parent_id' => $hub->id, 'sort' => 1]);

        $graph = $this->graph($page);

        $service = $graph->firstWhere('@type', 'Service');
        $this->assertSame('Hospedagem de sites', $service['name']);
        $this->assertCount(1, $service['offers']);
        $this->assertSame('1290', $service['offers'][0]['price']);
        $this->assertSame('Depende do escopo.', $graph->firstWhere('@type', 'FAQPage')['mainEntity'][0]['acceptedAnswer']['text']);
        $this->assertSame('Hospedagem de sites', $graph->firstWhere('@type', 'BreadcrumbList')['itemListElement'][1]['name']);
    }

    public function test_page_outside_the_menu_is_not_a_service(): void
    {
        MenuItem::query()->create(['locale' => 'pt-BR', 'group' => 'header', 'label' => 'Sobre', 'href' => '/sobre', 'sort' => 1]);

        $graph = $this->graph($this->page('sobre', 'Sobre nós'));

        $this->assertNull($graph->firstWhere('@type', 'Service'));
        $this->assertNotNull($graph->firstWhere('@type', 'AboutPage'));
    }

    public function test_price_parsing(): void
    {
        $this->assertSame('1290', StructuredData::parsePrice('R$ 1.290'));
        $this->assertSame('45.90', StructuredData::parsePrice('45,90/mês'));
        $this->assertSame('45.90', StructuredData::parsePrice('45.90'));
        $this->assertNull(StructuredData::parsePrice('Sob consulta'));
    }

    public function test_analysis_scores_ai_readiness_without_keyword(): void
    {
        $page = $this->page('automacao', 'Automação');

        $ai = $this->actingAs($this->makeEditor())
            ->postJson('/gestao/api/seo/analyze', ['kind' => 'page', 'id' => $page->id, 'sections' => [
                ['type' => 'site.hero', 'data' => ['title' => 'Automação de processos', 'subtitle' => '<p>O orçamento depende do número de sistemas integrados.</p>']],
            ]])
            ->assertOk()
            ->assertJsonPath('score', null)
            ->json('ai');

        $checks = collect($ai['checks'])->keyBy('id');
        $this->assertSame('ok', $checks['ai_price']['status']);
        $this->assertSame('bad', $checks['ai_depth']['status']);
        $this->assertIsInt($ai['score']);
    }

    public function test_site_readiness_flags_blocked_ai_bots_and_missing_budget(): void
    {
        $this->page('home', 'Quatro Ventos', [], ['template' => 'home']);
        SiteSetting::query()->first()->update(['robots_txt' => "User-agent: GPTBot\nDisallow: /\n\nUser-agent: *\nAllow: /\n"]);

        $checks = collect($this->actingAs($this->makeEditor())
            ->getJson('/gestao/api/seo/ai-readiness')
            ->assertOk()
            ->json('data.checks'))->keyBy('id');

        $this->assertSame('bad', $checks['robots_ai']['status']);
        $this->assertStringContainsString('GPTBot', $checks['robots_ai']['message']);
        $this->assertSame('bad', $checks['home_price']['status']);
        $this->assertSame('bad', $checks['about_page']['status']);
        $this->assertFalse(AiReadiness::blocksRoot("User-agent: *\nDisallow: /gestao\n", 'ClaudeBot'));
    }

    /**
     * @param  list<array<string, mixed>>  $sections
     * @param  array<string, mixed>  $overrides
     */
    private function page(string $slug, string $title, array $sections = [], array $overrides = []): Page
    {
        return Page::query()->create([
            'slug' => $slug,
            'template' => 'custom',
            'status' => 'published',
            'published_at' => now(),
            'seo_title' => $title,
            'content' => ['sections' => $sections],
            ...$overrides,
        ]);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function graph(Page $page): Collection
    {
        return collect(app(PublicContent::class)->page($page)['jsonld']['@graph']);
    }
}

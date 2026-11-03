<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CanonicalHostTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.url' => 'https://quatroventos.com.br']);
    }

    public function test_www_host_redirects_once_to_the_canonical_host(): void
    {
        $this->get('https://www.quatroventos.com.br/contato?origem=google')
            ->assertStatus(301)
            ->assertRedirect('https://quatroventos.com.br/contato?origem=google');

        $this->get('https://www.quatroventos.com.br/criacao-de-sites')
            ->assertRedirect('https://quatroventos.com.br/criacao-de-sites-sob-medida');

        $this->get('https://www.quatroventos.com.br/criacao-de-sites.php')
            ->assertRedirect('https://quatroventos.com.br/criacao-de-sites-sob-medida');

        $this->get('https://www.quatroventos.com.br/index.php')
            ->assertRedirect('https://quatroventos.com.br/');
    }

    public function test_home_canonical_matches_the_sitemap_url(): void
    {
        $this->page('home', 'home', null);

        $this->get('/')->assertSee('<link rel="canonical" href="'.url('/').'/" />', false);
    }

    public function test_other_hosts_are_not_redirected(): void
    {
        $this->get('http://quatroventos:8000/robots.txt')->assertOk();
    }

    public function test_migration_clears_canonicals_that_point_to_the_page_itself(): void
    {
        $home = $this->page('home', 'home', 'https://www.quatroventos.com.br/');
        $hosting = $this->page('hospedagem-de-sites', 'custom', 'https://www.quatroventos.com.br/hospedagem-de-sites');
        $external = $this->page('parceiro', 'custom', 'https://parceiro.com.br/artigo');
        $services = $this->page('servicos', 'custom', null, 'Serviços de IA, software, web e infraestrutura | Quatro Ventos');

        (require database_path('migrations/2026_11_03_101805_clear_self_canonicals_from_pages_table.php'))->up();

        $this->assertNull($home->fresh()->canonical);
        $this->assertNull($hosting->fresh()->canonical);
        $this->assertSame('https://parceiro.com.br/artigo', $external->fresh()->canonical);
        $this->assertSame('Serviços de IA, software e infraestrutura | Quatro Ventos', $services->fresh()->seo_title);
    }

    private function page(string $slug, string $template, ?string $canonical, string $title = 'Página'): Page
    {
        return Page::query()->create([
            'slug' => $slug,
            'template' => $template,
            'status' => 'published',
            'published_at' => now(),
            'seo_title' => $title,
            'canonical' => $canonical,
            'content' => ['sections' => []],
        ]);
    }
}

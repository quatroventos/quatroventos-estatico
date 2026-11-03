<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Services\PublicContent;
use App\Services\SitePrerenderer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Mockery\MockInterface;
use RuntimeException;
use Tests\TestCase;

class PublicContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_serves_prerendered_html_without_react(): void
    {
        Page::query()->create([
            'slug' => 'home',
            'template' => 'home',
            'status' => 'published',
            'published_at' => now(),
            'seo_title' => 'Início',
            'content' => ['sections' => [['id' => 'a', 'type' => 'site.hero', 'data' => ['title' => 'Processos melhores']]]],
        ]);

        $file = storage_path('app/prerender/pt-BR/index.html');
        $backup = is_file($file) ? File::get($file) : null;
        File::ensureDirectoryExists(dirname($file));
        File::put($file, '<main id="conteudo"><h1>Processos melhores</h1></main>');

        try {
            $this->get('/')
                ->assertOk()
                ->assertSee('<title>Início', false)
                ->assertSee('<main id="conteudo"><h1>Processos melhores</h1></main>', false)
                ->assertDontSee('id="site-root"', false)
                ->assertDontSee('window.__SITE__', false)
                ->assertDontSee('site/main.tsx', false);
        } finally {
            if ($backup === null) {
                File::delete($file);
            } else {
                File::put($file, $backup);
            }
        }
    }

    public function test_missing_fragment_is_rendered_on_demand(): void
    {
        $this->publishedHome();

        $this->withoutFragment(function (string $file): void {
            $this->mock(SitePrerenderer::class, function (MockInterface $mock) use ($file): void {
                $mock->shouldReceive('render')
                    ->once()
                    ->withArgs(fn (array $payloads): bool => count($payloads) === 1 && ($payloads[0]['page']['path'] ?? null) === '/')
                    ->andReturnUsing(function () use ($file): void {
                        File::ensureDirectoryExists(dirname($file));
                        File::put($file, '<main id="conteudo"><h1>Gerado na hora</h1></main>');
                    });
            });

            $this->get('/')
                ->assertOk()
                ->assertSee('<main id="conteudo"><h1>Gerado na hora</h1></main>', false)
                ->assertDontSee('site:prerender', false);
        });
    }

    public function test_failed_on_demand_render_never_shows_the_build_notice(): void
    {
        $this->publishedHome();

        $this->withoutFragment(function (): void {
            $this->mock(SitePrerenderer::class, function (MockInterface $mock): void {
                $mock->shouldReceive('render')->once()->andThrow(new RuntimeException('node indisponível'));
            });

            $this->get('/')
                ->assertStatus(503)
                ->assertHeader('Retry-After', '10')
                ->assertDontSee('site:prerender', false);
        });
    }

    private function publishedHome(): void
    {
        Page::query()->create([
            'slug' => 'home',
            'template' => 'home',
            'status' => 'published',
            'published_at' => now(),
            'seo_title' => 'Início',
            'content' => ['sections' => []],
        ]);
    }

    /**
     * Roda o teste sem o HTML da home e devolve o arquivo original no fim.
     *
     * @param  callable(string): void  $test
     */
    private function withoutFragment(callable $test): void
    {
        $file = storage_path('app/prerender/pt-BR/index.html');
        $backup = is_file($file) ? File::get($file) : null;
        File::delete($file);

        try {
            $test($file);
        } finally {
            File::delete($file);

            if ($backup !== null) {
                File::ensureDirectoryExists(dirname($file));
                File::put($file, $backup);
            }
        }
    }

    public function test_site_payload_links_only_published_policies(): void
    {
        Page::query()->create([
            'slug' => 'politica-de-privacidade',
            'template' => 'custom',
            'status' => 'published',
            'published_at' => now(),
            'seo_title' => 'Política de privacidade',
            'content' => ['sections' => []],
        ]);
        Page::query()->create([
            'slug' => 'politica-de-cookies',
            'template' => 'custom',
            'status' => 'draft',
            'seo_title' => 'Política de cookies',
            'content' => ['sections' => []],
        ]);

        $legal = (array) app(PublicContent::class)->site('pt-BR')['legal'];

        $this->assertSame(['privacy' => '/politica-de-privacidade'], $legal);
    }

    public function test_robots_allows_ai_crawlers(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('User-agent: GPTBot', false)
            ->assertSee('User-agent: OAI-SearchBot', false)
            ->assertSee('User-agent: Google-Extended', false)
            ->assertSee('User-agent: ClaudeBot', false)
            ->assertSee('User-agent: PerplexityBot', false)
            ->assertSee("User-agent: *\nAllow: /\nDisallow: /gestao\nDisallow: /admin\nDisallow: /login\nDisallow: /filament\nDisallow: /livewire\n", false)
            ->assertSee("User-agent: GPTBot\nAllow: /\nDisallow: /gestao\n", false)
            ->assertSee('Sitemap: '.url('/sitemap.xml'), false)
            ->assertSee(url('/llms.txt'), false)
            ->assertSee(url('/llm.txt'), false);
    }

    public function test_llms_txt_lists_indexable_pages(): void
    {
        Page::query()->create([
            'slug' => 'home',
            'template' => 'home',
            'status' => 'published',
            'published_at' => now(),
            'seo_title' => 'Início',
            'seo_description' => 'Software e IA.',
            'content' => ['sections' => []],
        ]);
        Page::query()->create([
            'slug' => 'obrigado',
            'template' => 'custom',
            'status' => 'published',
            'published_at' => now(),
            'seo_title' => 'Mensagem recebida',
            'robots' => 'noindex, follow',
            'content' => ['sections' => []],
        ]);

        $home = rtrim((string) config('app.url'), '/').'/';

        $this->get('/llms.txt')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee('# Quatro Ventos', false)
            ->assertSee('[Início]('.$home.'): Software e IA.', false)
            ->assertDontSee('obrigado', false);

        $this->get('/llm.txt')
            ->assertOk()
            ->assertSee('[Início]('.$home.'): Software e IA.', false);
    }

    public function test_old_urls_redirect_once_to_the_current_page(): void
    {
        Page::query()->create([
            'slug' => 'hospedagem-de-sites',
            'template' => 'custom',
            'status' => 'published',
            'published_at' => now(),
            'seo_title' => 'Hospedagem',
            'content' => ['sections' => []],
        ]);

        $this->get('/criacao-de-sites')
            ->assertStatus(301)
            ->assertRedirect('/criacao-de-sites-sob-medida');
        $this->get('/criacao-de-sites.php')
            ->assertStatus(301)
            ->assertRedirect('/criacao-de-sites-sob-medida');
        $this->get('/index.php')->assertRedirect('/');
        $this->get('/hospedagem-de-sites.php')->assertRedirect('/hospedagem-de-sites');
        $this->get('/arquivo-inexistente.php')->assertNotFound();
    }

    public function test_page_api_returns_sections(): void
    {
        Page::query()->create([
            'slug' => 'sobre',
            'template' => 'custom',
            'status' => 'published',
            'published_at' => now(),
            'seo_title' => 'Sobre',
            'content' => ['sections' => [['id' => 'a', 'type' => 'site.prose', 'data' => ['body' => 'Texto']]]],
        ]);

        $this->getJson('/api/pages/sobre')
            ->assertOk()
            ->assertJsonPath('sections.0.type', 'site.prose')
            ->assertJsonPath('sections.0.data.body', 'Texto');
    }
}

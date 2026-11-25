<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class MarkdownNegotiationTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_serves_markdown_when_accept_asks_for_it(): void
    {
        $this->publishHomeWithFragment('<main id="conteudo"><h1>Processos melhores com IA</h1><p>Automação e sistemas sob medida para a operação.</p></main>');

        $response = $this->get('/', ['Accept' => 'text/markdown'])
            ->assertOk()
            ->assertSee('# Processos melhores com IA', false)
            ->assertSee('Automação e sistemas sob medida para a operação.', false)
            ->assertDontSee('<!doctype html>', false);

        $this->assertStringContainsString('text/markdown', strtolower((string) $response->headers->get('Content-Type')));
        $this->assertVaryAccept($response->headers->get('Vary'));
    }

    public function test_home_keeps_html_for_browsers_and_varies_on_accept(): void
    {
        $this->publishHomeWithFragment('<main id="conteudo"><h1>Processos melhores com IA</h1><p>Automação e sistemas sob medida para a operação.</p></main>');

        $response = $this->get('/', ['Accept' => 'text/html'])
            ->assertOk()
            ->assertSee('<h1>Processos melhores com IA</h1>', false);

        $this->assertVaryAccept($response->headers->get('Vary'));
    }

    public function test_404_serves_markdown_with_useful_links(): void
    {
        config(['app.url' => 'http://localhost']);

        $this->get('/__qv-404-probe-teste', ['Accept' => 'text/markdown, text/html;q=0.9'])
            ->assertNotFound()
            ->assertHeader('Content-Type', 'text/markdown; charset=utf-8')
            ->assertHeader('Vary', 'Accept')
            ->assertSee('# Página não encontrada', false)
            ->assertSee('/sitemap.xml', false)
            ->assertSee('/llms.txt', false);
    }

    public function test_404_html_still_works_for_browsers(): void
    {
        $this->get('/__qv-404-probe-html', ['Accept' => 'text/html'])
            ->assertNotFound()
            ->assertHeader('Vary', 'Accept')
            ->assertSee('Esta página não existe mais.', false);
    }

    private function publishHomeWithFragment(string $html): void
    {
        Page::query()->create([
            'slug' => 'home',
            'template' => 'home',
            'status' => 'published',
            'published_at' => now(),
            'seo_title' => 'Quatro Ventos',
            'seo_description' => 'Processos melhores com IA.',
            'content' => ['sections' => [['id' => 'a', 'type' => 'site.hero', 'data' => ['title' => 'Processos melhores com IA']]]],
        ]);

        $file = storage_path('app/prerender/pt-BR/index.html');
        File::ensureDirectoryExists(dirname($file));
        File::put($file, $html);
    }

    private function assertVaryAccept(?string $vary): void
    {
        $tokens = array_map(
            fn (string $token): string => strtolower(trim($token)),
            array_filter(explode(',', (string) $vary))
        );

        $this->assertContains('accept', $tokens);
    }
}

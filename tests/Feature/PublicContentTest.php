<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_renders_react_shell_with_payload(): void
    {
        Page::query()->create([
            'slug' => 'home',
            'template' => 'home',
            'status' => 'published',
            'published_at' => now(),
            'seo_title' => 'Início',
            'content' => ['sections' => [['id' => 'a', 'type' => 'site.hero', 'data' => ['title' => 'Processos melhores']]]],
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('id="site-root"', false)
            ->assertSee('Processos melhores', false);
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

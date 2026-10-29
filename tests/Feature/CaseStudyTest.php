<?php

namespace Tests\Feature;

use App\Models\CaseStudy;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CaseStudyTest extends TestCase
{
    use RefreshDatabase;

    private function servicePage(string $slug, array $sections = []): Page
    {
        return Page::query()->create([
            'slug' => $slug,
            'template' => 'custom',
            'status' => 'published',
            'published_at' => now(),
            'seo_title' => 'Serviço '.$slug.' | Quatro Ventos',
            'content' => ['sections' => $sections],
        ]);
    }

    private function makeCase(array $overrides = []): CaseStudy
    {
        return CaseStudy::query()->create(array_merge([
            'slug' => 'digio',
            'status' => 'published',
            'client' => 'Digio',
            'title' => 'Web app de onboarding',
            'summary' => 'Resumo do case.',
            'sector' => 'financas',
            'services' => ['software-sob-medida'],
            'cover_path' => 'https://cdn.test/capa.png',
            'gallery' => [
                ['path' => 'https://cdn.test/1.png', 'caption' => 'Tela 1'],
                ['path' => 'https://cdn.test/2.png', 'caption' => 'Tela 2'],
                ['path' => 'https://cdn.test/3.png', 'caption' => 'Tela 3'],
                ['path' => 'https://cdn.test/4.png', 'caption' => 'Tela 4'],
            ],
            'challenge' => '<p>Desafio</p>',
            'solution' => '<p>Solução</p>',
            'published_at' => now(),
        ], $overrides));
    }

    public function test_editor_can_create_and_update_case(): void
    {
        $this->servicePage('software-sob-medida');
        $editor = $this->makeEditor();

        $id = $this->actingAs($editor)
            ->postJson('/gestao/api/cases', [
                'slug' => 'digio',
                'status' => 'published',
                'client' => 'Digio',
                'title' => 'Web app de onboarding',
                'services' => ['software-sob-medida', ''],
                'gallery' => [['path' => '/storage/media/1.png', 'caption' => 'Tela']],
                'deliverables' => ['Painel', ' '],
                'results' => [['value' => '', 'label' => '']],
                'testimonial' => ['quote' => '', 'author' => 'X'],
                'featured' => true,
            ])
            ->assertCreated()
            ->assertJsonPath('data.services', ['software-sob-medida'])
            ->assertJsonPath('data.deliverables', ['Painel'])
            ->assertJsonPath('data.results', [])
            ->assertJsonPath('data.testimonial', null)
            ->json('data.id');

        $this->assertNotNull(CaseStudy::query()->find($id)?->published_at);

        $this->actingAs($editor)
            ->getJson('/gestao/api/cases')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'digio')
            ->assertJsonPath('services.0.value', 'software-sob-medida');

        $this->actingAs($editor)
            ->putJson("/gestao/api/cases/{$id}", [
                'slug' => 'digio',
                'status' => 'draft',
                'client' => 'Digio',
                'title' => 'Novo título',
            ])
            ->assertOk()
            ->assertJsonPath('data.title', 'Novo título');
    }

    public function test_case_slug_must_be_unique(): void
    {
        $this->makeCase();

        $this->actingAs($this->makeEditor())
            ->postJson('/gestao/api/cases', [
                'slug' => 'digio',
                'status' => 'draft',
                'client' => 'Outro',
                'title' => 'Outro',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors('slug');
    }

    public function test_case_page_renders_with_seo_and_payload(): void
    {
        $this->servicePage('software-sob-medida');
        $this->makeCase();

        $html = $this->get('/cases/digio')->assertOk()->getContent();

        $this->assertStringContainsString('<title>Web app de onboarding | Case Digio | Quatro Ventos</title>', $html);
        $this->assertStringContainsString('content="Resumo do case."', $html);
        $this->assertStringContainsString('https://cdn.test/capa.png', $html);
        $this->assertStringContainsString('application/ld+json', $html);
        $this->assertStringContainsString('BreadcrumbList', $html);
    }

    public function test_draft_case_is_not_public(): void
    {
        $this->makeCase(['status' => 'draft']);

        $this->get('/cases/digio')->assertNotFound();
        $this->getJson('/api/cases/digio')->assertNotFound();
        $this->getJson('/api/cases')->assertOk()->assertJsonCount(0, 'data');
    }

    public function test_cases_api_filters_by_service(): void
    {
        $this->servicePage('software-sob-medida');
        $this->makeCase();
        $this->makeCase(['slug' => 'ivorecap', 'client' => 'Ivorecap', 'services' => ['desenvolvimento-de-ecommerce']]);

        $this->getJson('/api/cases')->assertOk()->assertJsonCount(2, 'data');
        $this->getJson('/api/cases?servico=software-sob-medida')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'digio')
            ->assertJsonCount(3, 'data.0.gallery')
            ->assertJsonPath('data.0.services.0.label', 'Serviço software-sob-medida');

        $this->getJson('/api/cases/digio')
            ->assertOk()
            ->assertJsonPath('template', 'case')
            ->assertJsonCount(4, 'case.gallery')
            ->assertJsonPath('case.next.slug', 'ivorecap');
    }

    public function test_service_page_resolves_selected_and_automatic_cases(): void
    {
        $this->makeCase();
        $this->makeCase(['slug' => 'dialogos', 'client' => 'Diálogos de Carreira']);
        $this->makeCase(['slug' => 'rascunho', 'client' => 'Rascunho', 'status' => 'draft']);

        $this->servicePage('software-sob-medida', [
            ['id' => 'a', 'type' => 'site.cases', 'data' => ['items' => ['dialogos', 'rascunho'], 'layout' => 'par']],
            ['id' => 'b', 'type' => 'site.cases', 'data' => ['layout' => 'destaque']],
        ]);

        $this->getJson('/api/pages/software-sob-medida')
            ->assertOk()
            ->assertJsonCount(1, 'sections.0.data.cases')
            ->assertJsonPath('sections.0.data.cases.0.slug', 'dialogos')
            ->assertJsonCount(1, 'sections.1.data.cases')
            ->assertJsonPath('sections.1.data.layout', 'destaque');
    }

    public function test_cases_list_section_returns_all_published(): void
    {
        $this->makeCase();
        $this->makeCase(['slug' => 'ivorecap', 'client' => 'Ivorecap', 'featured' => true]);

        $this->servicePage('cases', [
            ['id' => 'a', 'type' => 'site.cases-list', 'data' => ['filter' => true]],
        ]);

        $this->getJson('/api/pages/cases')
            ->assertOk()
            ->assertJsonCount(2, 'sections.0.data.cases')
            ->assertJsonPath('sections.0.data.cases.0.slug', 'ivorecap');
    }

    public function test_admin_page_editor_keeps_site_sections(): void
    {
        $page = $this->servicePage('software-sob-medida', [
            ['id' => 'a', 'type' => 'site.hero', 'data' => ['title' => 'Hero']],
            ['id' => 'b', 'type' => 'site.cases', 'data' => ['items' => ['digio']]],
        ]);

        $this->actingAs($this->makeEditor())
            ->getJson("/gestao/api/pages/{$page->slug}")
            ->assertOk()
            ->assertJsonCount(2, 'data.content.sections')
            ->assertJsonPath('schema.1.type', 'site.cases');

        $this->actingAs($this->makeEditor())
            ->putJson("/gestao/api/pages/{$page->slug}", [
                'seo_title' => 'Software',
                'content' => ['sections' => [
                    ['id' => 'a', 'type' => 'site.hero', 'data' => ['title' => 'Hero']],
                    ['id' => 'b', 'type' => 'site.cases', 'data' => ['items' => ['digio'], 'layout' => 'destaque']],
                ]],
            ])
            ->assertOk()
            ->assertJsonCount(2, 'data.content.sections');
    }

    public function test_sitemap_lists_published_cases(): void
    {
        $this->makeCase();
        $this->makeCase(['slug' => 'rascunho', 'status' => 'draft']);

        $xml = $this->get('/sitemap.xml')->assertOk()->getContent();

        $this->assertStringContainsString(url('/cases/digio'), $xml);
        $this->assertStringNotContainsString(url('/cases/rascunho'), $xml);
    }
}

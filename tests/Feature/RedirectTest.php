<?php

namespace Tests\Feature;

use App\Models\CaseStudy;
use App\Models\Page;
use App\Models\Redirect;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_renaming_a_published_page_creates_a_single_hop_redirect(): void
    {
        $page = $this->makePage('servico-a');

        $page->update(['slug' => 'servico-b']);
        $page->update(['slug' => 'servico-c']);

        $this->get('/servico-a?utm_source=google')
            ->assertStatus(301)
            ->assertRedirect('/servico-c?utm_source=google');
        $this->get('/servico-b')->assertStatus(301)->assertRedirect('/servico-c');
        $this->get('/servico-c')->assertOk();

        $this->assertTrue(Redirect::query()->where('source', '/servico-a')->value('automatic'));
        $this->assertSame(1, Redirect::query()->where('source', '/servico-a')->value('hits'));
    }

    public function test_renaming_back_releases_the_original_path(): void
    {
        $page = $this->makePage('servico-a');

        $page->update(['slug' => 'servico-b']);
        $page->update(['slug' => 'servico-a']);

        $this->get('/servico-a')->assertOk();
        $this->get('/servico-b')->assertRedirect('/servico-a');
        $this->assertFalse(Redirect::query()->where('source', '/servico-a')->exists());
    }

    public function test_draft_rename_does_not_create_redirect(): void
    {
        $page = $this->makePage('rascunho', ['status' => 'draft']);

        $page->update(['slug' => 'rascunho-novo']);

        $this->assertFalse(Redirect::query()->where('source', '/rascunho')->exists());
    }

    public function test_renaming_a_published_case_redirects_the_case_url(): void
    {
        $case = CaseStudy::query()->create([
            'slug' => 'case-antigo',
            'status' => 'published',
            'client' => 'Cliente',
            'title' => 'Case',
            'content' => ['sections' => []],
        ]);

        $case->update(['slug' => 'case-novo']);

        $this->get('/cases/case-antigo')->assertStatus(301)->assertRedirect('/cases/case-novo');
    }

    public function test_admin_manages_redirects(): void
    {
        $this->makeSettings();
        $this->makePage('contato');
        $admin = $this->makeAdmin();

        $id = $this->actingAs($admin)
            ->postJson('/gestao/api/redirects', ['source' => '/Pagina-Velha/?x=1', 'target' => '/contato'])
            ->assertCreated()
            ->assertJsonPath('data.source', '/pagina-velha')
            ->assertJsonPath('data.automatic', false)
            ->json('data.id');

        $this->postJson('/gestao/api/redirects', ['source' => '/outra', 'target' => '/pagina-velha'])
            ->assertCreated()
            ->assertJsonPath('data.target', '/contato');

        $this->postJson('/gestao/api/redirects', ['source' => '/pagina-velha', 'target' => '/x'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('source');

        $this->postJson('/gestao/api/redirects', ['source' => '/contato', 'target' => '/x'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('source');

        $this->putJson('/gestao/api/redirects/'.$id, ['source' => '/pagina-velha', 'target' => 'https://exemplo.com/destino'])
            ->assertOk()
            ->assertJsonPath('data.target', 'https://exemplo.com/destino');

        $this->get('/pagina-velha')->assertRedirect('https://exemplo.com/destino');

        $this->deleteJson('/gestao/api/redirects/'.$id)->assertOk();

        $this->get('/pagina-velha')->assertNotFound();
    }

    private function makeSettings(): SiteSetting
    {
        return SiteSetting::query()->create(['name' => 'Quatro Ventos']);
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function makePage(string $slug, array $overrides = []): Page
    {
        return Page::query()->create(array_merge([
            'slug' => $slug,
            'template' => 'custom',
            'status' => 'published',
            'seo_title' => ucfirst($slug),
            'content' => ['sections' => []],
            'published_at' => now(),
        ], $overrides));
    }
}

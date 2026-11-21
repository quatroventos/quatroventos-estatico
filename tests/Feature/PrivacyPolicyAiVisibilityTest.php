<?php

namespace Tests\Feature;

use App\Cms\PageContentMigrator;
use App\Cms\PrivacyPolicyAiVisibilityPatch;
use App\Models\Page;
use App\Support\Locales;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrivacyPolicyAiVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_privacy_policy_patch_mentions_stored_url_and_non_commercial_study_use(): void
    {
        $page = Page::query()->create([
            'slug' => 'politica-de-privacidade',
            'locale' => Locales::default(),
            'template' => 'custom',
            'status' => 'published',
            'seo_title' => 'Política de privacidade | Quatro Ventos',
            'seo_description' => 'Privacidade',
            'published_at' => now(),
            'content' => PageContentMigrator::normalize([
                'sections' => [
                    [
                        'id' => 'hero-1',
                        'type' => 'site.hero',
                        'data' => [
                            'title' => 'Política de *privacidade.*',
                            'subtitle' => '<p>Texto. Última atualização: 1º de novembro de 2026.</p>',
                        ],
                    ],
                    [
                        'id' => 'prose-2',
                        'type' => 'site.prose',
                        'data' => [
                            'title' => 'Quais dados coletamos',
                            'body' => '<p>Quando você entra em contato: nome e e-mail.</p>',
                        ],
                    ],
                    [
                        'id' => 'prose-3',
                        'type' => 'site.prose',
                        'data' => [
                            'title' => 'Para que usamos',
                            'body' => '<ul><li>Responder seu contato.</li></ul>',
                        ],
                    ],
                    [
                        'id' => 'prose-4',
                        'type' => 'site.prose',
                        'data' => [
                            'title' => 'Bases legais',
                            'body' => '<ul><li><strong>Legítimo interesse</strong>, para a segurança do site e a prevenção de fraudes.</li></ul>',
                        ],
                    ],
                    [
                        'id' => 'prose-6',
                        'type' => 'site.prose',
                        'data' => [
                            'title' => 'Por quanto tempo guardamos',
                            'body' => '<p>Os dados de contato ficam guardados enquanto durar a conversa.</p>',
                        ],
                    ],
                ],
            ], 'custom'),
        ]);

        $this->assertSame(1, PrivacyPolicyAiVisibilityPatch::apply());

        $page->refresh();
        $html = collect($page->sections())->pluck('data')->map(fn ($data) => ($data['body'] ?? '').($data['subtitle'] ?? ''))->implode(' ');

        $this->assertStringContainsString('Como aparecer no ChatGPT', $html);
        $this->assertStringContainsString('não são usados para fins comerciais', $html);
        $this->assertStringContainsString('finalidade de estudo', $html);
        $this->assertStringContainsString('21 de novembro de 2026', $html);
        $this->assertSame(0, PrivacyPolicyAiVisibilityPatch::apply());
    }
}

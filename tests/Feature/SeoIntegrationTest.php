<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\SeoIntegration;
use App\Models\SiteSetting;
use App\Services\Content\SectionCopyWriter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SeoIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        SiteSetting::query()->create(['name' => 'Quatro Ventos', 'og_locale' => 'pt_BR', 'twitter_card' => 'summary_large_image']);
    }

    public function test_secrets_are_encrypted_write_only_and_applied_to_the_config(): void
    {
        $editor = $this->makeEditor();

        $response = $this->actingAs($editor)
            ->putJson('/gestao/api/seo/integrations', [
                'serpbear_url' => 'http://serpbear.test/',
                'serpbear_domain' => 'quatroventos.com.br',
                'serpbear_country' => 'br',
                'serpbear_devices' => 'both',
                'openseo_paid_enabled' => false,
                'secrets' => ['serpbear_api_key' => 'sb_secret', 'openai_api_key' => 'sk-secret'],
            ])
            ->assertOk()
            ->assertJsonPath('data.secrets.serpbear_api_key.set', true)
            ->assertJsonPath('data.secrets.serpbear_password.set', false)
            ->assertJsonPath('data.values.serpbear_domain', 'quatroventos.com.br')
            ->assertJsonPath('data.paid', false);

        $this->assertStringNotContainsString('sb_secret', $response->getContent());
        $this->assertStringNotContainsString('sk-secret', $response->getContent());
        $this->assertNotSame('sb_secret', DB::table('seo_integrations')->value('serpbear_api_key'));
        $this->assertSame('sb_secret', config('services.serpbear.key'));
        $this->assertSame('http://serpbear.test', config('services.serpbear.url'));
        $this->assertSame('BR', config('services.serpbear.country'));
        $this->assertSame('sk-secret', config('services.openai.key'));

        $this->actingAs($editor)
            ->putJson('/gestao/api/seo/integrations', ['serpbear_domain' => 'quatroventos.com.br', 'secrets' => ['serpbear_api_key' => '']])
            ->assertOk();

        $this->assertSame('sb_secret', SeoIntegration::current()->serpbear_api_key);

        $this->actingAs($editor)
            ->putJson('/gestao/api/seo/integrations', ['clear' => ['serpbear_api_key']])
            ->assertOk()
            ->assertJsonPath('data.secrets.serpbear_api_key.set', false);

        $this->assertNull(SeoIntegration::current()->serpbear_api_key);
    }

    public function test_custom_prompts_reach_the_ai_and_the_default_text_is_not_stored(): void
    {
        config(['services.openai.base_url' => 'https://openai.test/v1']);
        $editor = $this->makeEditor();

        $this->actingAs($editor)
            ->putJson('/gestao/api/seo/integrations', ['prompt_writer' => SectionCopyWriter::defaultPrompt()])
            ->assertOk()
            ->assertJsonPath('data.values.prompt_writer', null);

        $this->actingAs($editor)
            ->putJson('/gestao/api/seo/integrations', [
                'prompt_writer' => 'Escreva como a Quatro Ventos fala com clientes.',
                'prompt_seo_description' => 'Cite Curitiba quando fizer sentido.',
                'secrets' => ['openai_api_key' => 'sk-test'],
            ])
            ->assertOk();

        Http::fake([
            'openai.test/*' => Http::response(['output' => [['type' => 'message', 'content' => [['type' => 'output_text', 'text' => json_encode(['value' => 'Agentes de IA que atendem clientes no WhatsApp, integrados ao seu sistema, com resposta rápida e histórico em um só lugar.'])]]]]]),
        ]);

        $page = Page::query()->create(['slug' => 'agentes-de-ia', 'template' => 'custom', 'status' => 'published', 'published_at' => now(), 'seo_title' => 'Agentes de IA', 'content' => ['sections' => []]]);

        $this->actingAs($editor)
            ->postJson('/gestao/api/sections/copy', [
                'kind' => 'page',
                'target' => 'seo',
                'page_id' => $page->id,
                'mode' => 'melhorar',
                'field' => 'seo_description',
                'sections' => [['type' => 'site.prose', 'data' => ['title' => 'Atendimento', 'body' => '<p>Respondemos clientes no WhatsApp.</p>']]],
            ])
            ->assertOk();

        Http::assertSent(function (Request $request): bool {
            $body = (string) json_encode($request->data(), JSON_UNESCAPED_UNICODE);

            return $request->hasHeader('Authorization', 'Bearer sk-test')
                && str_contains($body, 'Escreva como a Quatro Ventos fala com clientes.')
                && ! str_contains($body, 'AI Slop')
                && str_contains($body, 'Instruções do site para este campo: Cite Curitiba quando fizer sentido.');
        });
    }

    public function test_serpbear_connection_test_reports_missing_domain_and_login(): void
    {
        config([
            'services.serpbear.url' => 'http://serpbear.test',
            'services.serpbear.key' => 'sb_key',
            'services.serpbear.domain' => 'quatroventos.com.br',
            'services.serpbear.username' => 'admin',
            'services.serpbear.password' => 'errada',
        ]);
        Http::fake([
            'serpbear.test/api/domains' => Http::response(['domains' => [['domain' => 'outro.com.br', 'keywordCount' => 4]]]),
            'serpbear.test/api/login' => Http::response(['success' => false, 'error' => 'Incorrect Password'], 401),
        ]);

        $this->actingAs($this->makeEditor())
            ->postJson('/gestao/api/seo/integrations/test/serpbear')
            ->assertOk()
            ->assertJsonPath('ok', false)
            ->assertJsonPath('domains.0.domain', 'outro.com.br')
            ->assertJsonPath('message', 'Chave de API ok, mas o domínio quatroventos.com.br não existe no SerpBear. Cadastre-o lá ou escolha um da lista. O SerpBear recusou o login. Confira usuário e senha em SEO > Integrações.');
    }

    public function test_guest_cannot_read_integrations(): void
    {
        $this->getJson('/gestao/api/seo/integrations')->assertUnauthorized();
    }
}

<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SerpBearRankingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        SiteSetting::query()->create(['name' => 'Quatro Ventos', 'og_locale' => 'pt_BR', 'twitter_card' => 'summary_large_image']);

        config([
            'services.serpbear.url' => 'http://serpbear.test',
            'services.serpbear.key' => 'sb_key',
            'services.serpbear.username' => null,
            'services.serpbear.password' => null,
            'services.serpbear.domain' => 'quatroventos.com.br',
            'services.serpbear.country' => 'BR',
            'services.serpbear.devices' => 'both',
        ]);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function keywords(): array
    {
        return [
            ['ID' => 1, 'keyword' => 'criação de sites', 'device' => 'desktop', 'country' => 'BR', 'position' => 7, 'url' => 'https://quatroventos.com.br/sites', 'lastUpdated' => '2026-10-31T10:00:00.000Z', 'updating' => false, 'lastUpdateError' => false, 'history' => ['2026-10-29' => 12, '2026-10-30' => 9, '2026-10-31' => 7]],
            ['ID' => 2, 'keyword' => 'criação de sites', 'device' => 'mobile', 'country' => 'BR', 'position' => 0, 'url' => '', 'lastUpdated' => '2026-10-31T10:00:00.000Z', 'updating' => false, 'lastUpdateError' => ['date' => '2026-10-31', 'error' => 'Proxy timeout', 'scraper' => 'proxy'], 'history' => ['2026-10-31' => 0]],
            ['ID' => 3, 'keyword' => 'landing page', 'device' => 'desktop', 'country' => 'BR', 'position' => 3, 'url' => 'https://quatroventos.com.br/lp', 'lastUpdated' => '2026-10-30T10:00:00.000Z', 'updating' => true, 'lastUpdateError' => false, 'history' => ['2026-10-30' => 3]],
        ];
    }

    public function test_report_merges_devices_and_compares_with_the_previous_check(): void
    {
        Http::fake(['serpbear.test/api/keywords*' => Http::response(['keywords' => $this->keywords()])]);

        $this->actingAs($this->makeEditor())
            ->getJson('/gestao/api/seo/rank')
            ->assertOk()
            ->assertJsonPath('state', 'ready')
            ->assertJsonPath('can_manage', false)
            ->assertJsonPath('updating', true)
            ->assertJsonPath('devices', ['desktop', 'mobile'])
            ->assertJsonPath('link', 'http://serpbear.test/domain/quatroventos-com-br')
            ->assertJsonCount(2, 'rows')
            ->assertJsonPath('rows.0.keyword', 'landing page')
            ->assertJsonPath('rows.0.mobile', null)
            ->assertJsonPath('rows.1.desktop.position', 7)
            ->assertJsonPath('rows.1.desktop.previous', 9)
            ->assertJsonPath('rows.1.desktop.best', 7)
            ->assertJsonPath('rows.1.mobile.position', null)
            ->assertJsonPath('rows.1.mobile.error', 'Proxy timeout');

        Http::assertSent(fn (Request $request) => $request->hasHeader('Authorization', 'Bearer sb_key')
            && str_contains($request->url(), 'domain=quatroventos.com.br'));
    }

    public function test_refresh_queues_all_domain_keywords_with_the_api_key(): void
    {
        Http::fake([
            'serpbear.test/api/refresh*' => Http::response(['keywords' => []]),
            'serpbear.test/api/keywords*' => Http::response(['keywords' => $this->keywords()]),
        ]);

        $this->actingAs($this->makeEditor())->postJson('/gestao/api/seo/rank/refresh')->assertOk()->assertJsonPath('state', 'ready');

        Http::assertSent(fn (Request $request) => $request->method() === 'POST'
            && str_contains($request->url(), '/api/refresh?id=all&domain=quatroventos.com.br'));
    }

    public function test_adding_keywords_logs_in_and_creates_one_entry_per_device(): void
    {
        config(['services.serpbear.username' => 'admin', 'services.serpbear.password' => 'segredo']);
        Http::fake([
            'serpbear.test/api/login' => Http::response(['success' => true], 200, ['Set-Cookie' => 'token=jwt_1; Path=/; HttpOnly']),
            'serpbear.test/api/keywords*' => fn (Request $request) => $request->method() === 'POST'
                ? Http::response(['keywords' => []], 201)
                : Http::response(['keywords' => $this->keywords()]),
        ]);

        $this->actingAs($this->makeEditor())
            ->postJson('/gestao/api/seo/rank/keywords', ['keywords' => [' sites em curitiba ', 'sites em curitiba']])
            ->assertOk()
            ->assertJsonPath('can_manage', true);

        Http::assertSent(fn (Request $request) => $request->method() === 'POST'
            && str_ends_with($request->url(), '/api/keywords')
            && $request->hasHeader('Cookie', 'token=jwt_1')
            && $request['keywords'] === [
                ['keyword' => 'sites em curitiba', 'device' => 'desktop', 'country' => 'BR', 'domain' => 'quatroventos.com.br', 'tags' => '', 'city' => ''],
                ['keyword' => 'sites em curitiba', 'device' => 'mobile', 'country' => 'BR', 'domain' => 'quatroventos.com.br', 'tags' => '', 'city' => ''],
            ]);
    }

    public function test_managing_keywords_without_login_explains_what_is_missing(): void
    {
        Http::fake();

        $this->actingAs($this->makeEditor())
            ->deleteJson('/gestao/api/seo/rank/keywords', ['ids' => [1, 2]])
            ->assertStatus(422)
            ->assertJsonPath('message', 'Para adicionar ou remover palavras, preencha usuário e senha do SerpBear em SEO > Integrações.');

        Http::assertNothingSent();
    }

    public function test_without_configuration_the_block_reports_setup(): void
    {
        config(['services.serpbear.key' => null]);
        Http::fake();

        $this->actingAs($this->makeEditor())->getJson('/gestao/api/seo/rank')->assertOk()->assertJsonPath('state', 'unconfigured');

        Http::assertNothingSent();
    }
}

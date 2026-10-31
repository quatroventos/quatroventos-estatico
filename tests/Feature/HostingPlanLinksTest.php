<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Services\PublicContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HostingPlanLinksTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_saves_known_plan_links_and_drops_the_rest(): void
    {
        SiteSetting::query()->create(['name' => 'Quatro Ventos']);

        $this->actingAs($this->makeAdmin())
            ->putJson('/gestao/api/site', [
                'name' => 'Quatro Ventos',
                'plan_links' => [
                    'sites-basic' => 'https://loja.example.com/basic',
                    'wp-pro' => '',
                    'inexistente' => 'https://loja.example.com/x',
                ],
            ])
            ->assertOk();

        $this->assertSame(['sites-basic' => 'https://loja.example.com/basic'], SiteSetting::query()->first()->plan_links);
    }

    public function test_plan_link_must_be_http_url(): void
    {
        SiteSetting::query()->create(['name' => 'Quatro Ventos']);

        $this->actingAs($this->makeAdmin())
            ->putJson('/gestao/api/site', [
                'name' => 'Quatro Ventos',
                'plan_links' => ['sites-basic' => 'javascript:alert(1)'],
            ])
            ->assertJsonValidationErrors('plan_links.sites-basic');
    }

    public function test_public_payload_exposes_plan_links(): void
    {
        SiteSetting::query()->create([
            'name' => 'Quatro Ventos',
            'plan_links' => ['woo-pro' => 'https://loja.example.com/woo-pro'],
        ]);

        $site = app(PublicContent::class)->site();

        $this->assertSame(['woo-pro' => 'https://loja.example.com/woo-pro'], (array) $site['plan_links']);
    }
}

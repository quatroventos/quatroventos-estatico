<?php

namespace Tests\Feature;

use App\Models\AiVisibilityReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiVisibilityReportAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_and_view_ai_visibility_reports(): void
    {
        $admin = $this->makeAdmin();
        $report = AiVisibilityReport::query()->create([
            'public_id' => 'abc123ReportId0001',
            'url' => 'https://exemplo.com.br/',
            'host' => 'exemplo.com.br',
            'score' => 55,
            'band' => 'medio',
            'niche' => 'Serviços',
            'checks' => [['label' => 'Robots', 'status' => 'good', 'points' => 20, 'max' => 20, 'message' => 'OK']],
            'fixes' => [],
            'response_ms' => 120,
            'ip' => '203.0.113.10',
        ]);

        $this->actingAs($admin)
            ->getJson('/gestao/api/ai-visibility-reports')
            ->assertOk()
            ->assertJsonPath('data.0.public_id', $report->public_id)
            ->assertJsonPath('data.0.host', 'exemplo.com.br');

        $this->actingAs($admin)
            ->getJson('/gestao/api/ai-visibility-reports/'.$report->public_id)
            ->assertOk()
            ->assertJsonPath('data.score', 55)
            ->assertJsonPath('data.checks.0.label', 'Robots');
    }

    public function test_admin_can_delete_ai_visibility_report(): void
    {
        $admin = $this->makeAdmin();
        $report = AiVisibilityReport::query()->create([
            'public_id' => 'abc123ReportId0002',
            'url' => 'https://exemplo.com.br/',
            'host' => 'exemplo.com.br',
            'score' => 10,
            'band' => 'baixo',
            'checks' => [],
            'fixes' => [],
        ]);

        $this->actingAs($admin)
            ->deleteJson('/gestao/api/ai-visibility-reports/'.$report->public_id)
            ->assertNoContent();

        $this->assertDatabaseMissing('ai_visibility_reports', ['id' => $report->id]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\AiVisibilityReport;
use App\Models\MenuItem;
use App\Support\Locales;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MenuAiVisibilityLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_menu_migration_replaces_diagnostico_with_ai_visibility_tool(): void
    {
        $item = MenuItem::query()->create([
            'parent_id' => null,
            'label' => 'Diagnóstico gratuito de site',
            'href' => '/diagnostico-de-site',
            'page_id' => null,
            'group' => 'footer',
            'locale' => Locales::default(),
            'sort' => 4,
            'is_active' => true,
        ]);

        DB::table('migrations')
            ->where('migration', '2026_11_21_115627_replace_diagnostico_menu_item_with_ai_visibility_tool')
            ->delete();

        Artisan::call('migrate', [
            '--path' => 'database/migrations/2026_11_21_115627_replace_diagnostico_menu_item_with_ai_visibility_tool.php',
            '--force' => true,
        ]);

        $item->refresh();

        $this->assertSame('Teste se a IA lê o seu site', $item->label);
        $this->assertSame('/'.AiVisibilityReport::PATH, $item->href);
    }
}

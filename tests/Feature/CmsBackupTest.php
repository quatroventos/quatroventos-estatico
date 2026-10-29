<?php

namespace Tests\Feature;

use App\Models\Backup;
use App\Models\Page;
use App\Models\SiteSetting;
use App\Services\BackupService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CmsBackupTest extends TestCase
{
    use RefreshDatabase;

    public function test_editor_can_manage_backups(): void
    {
        $this->makeSettings();

        $this->actingAs($this->makeEditor())
            ->getJson('/gestao/api/backup')
            ->assertOk();
    }

    public function test_admin_can_list_and_update_backup_settings(): void
    {
        $this->makeSettings();

        $this->actingAs($this->makeAdmin())
            ->getJson('/gestao/api/backup')
            ->assertOk()
            ->assertJsonPath('data.hour', 3)
            ->assertJsonPath('data.retention_days', 7);

        $this->actingAs($this->makeAdmin())
            ->putJson('/gestao/api/backup', [
                'hour' => 4,
                'retention_days' => 10,
            ])
            ->assertOk()
            ->assertJsonPath('data.hour', 4)
            ->assertJsonPath('data.retention_days', 10);

        $this->assertSame(4, (int) SiteSetting::query()->value('backup_hour'));
        $this->assertSame(10, (int) SiteSetting::query()->value('backup_retention_days'));
    }

    public function test_admin_can_run_download_restore_and_delete_backup(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        $this->makeSettings(['name' => 'RBA']);
        $this->makePage();

        Storage::disk('public')->put('media/logo.txt', 'logo');
        Storage::disk('local')->put('curriculos/cv.pdf', 'pdf');

        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->postJson('/gestao/api/backup/run')
            ->assertOk()
            ->assertJsonPath('data.items.0.status', 'ok');

        $backup = Backup::query()->firstOrFail();
        $this->assertTrue(Storage::disk('local')->exists($backup->filename));

        $this->actingAs($admin)
            ->get("/gestao/api/backup/{$backup->id}/download")
            ->assertOk();

        SiteSetting::query()->firstOrFail()->update(['name' => 'Alterado']);
        Page::query()->where('slug', 'home')->update(['seo_title' => 'Outro']);
        Storage::disk('public')->put('media/logo.txt', 'novo');

        $this->actingAs($admin)
            ->postJson("/gestao/api/backup/{$backup->id}/restore")
            ->assertOk();

        $this->assertSame('RBA', SiteSetting::query()->value('name'));
        $this->assertSame('Home', Page::query()->where('slug', 'home')->value('seo_title'));
        $this->assertSame('logo', Storage::disk('public')->get('media/logo.txt'));
        $this->assertSame('pdf', Storage::disk('local')->get('curriculos/cv.pdf'));

        $this->actingAs($admin)
            ->deleteJson("/gestao/api/backup/{$backup->id}")
            ->assertOk();

        $this->assertSoftDeleted('backups', ['id' => $backup->id]);
        $this->assertTrue(Storage::disk('local')->exists($backup->filename));

        $this->actingAs($admin)
            ->getJson('/gestao/api/backup?trashed=1')
            ->assertOk()
            ->assertJsonPath('data.items.0.id', $backup->id);

        $this->actingAs($admin)
            ->postJson("/gestao/api/backup/{$backup->id}/undelete")
            ->assertOk()
            ->assertJsonPath('data.items.0.id', $backup->id);

        $this->assertDatabaseHas('backups', ['id' => $backup->id, 'deleted_at' => null]);
    }

    public function test_retention_prunes_old_backups(): void
    {
        Storage::fake('local');
        $this->makeSettings(['backup_retention_days' => 7]);

        Storage::disk('local')->put('backups/old.zip', 'zip');

        $old = Backup::query()->create([
            'filename' => 'backups/old.zip',
            'size' => 3,
            'status' => 'ok',
            'trigger' => 'schedule',
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(10),
        ]);

        app(BackupService::class)->prune();

        $this->assertDatabaseMissing('backups', ['id' => $old->id]);
        $this->assertFalse(Storage::disk('local')->exists('backups/old.zip'));
    }

    public function test_backup_run_command_creates_ok_backup(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        $this->makeSettings();
        $this->makePage();

        $this->assertSame(0, Artisan::call('backup:run'));
        $this->assertSame('ok', Backup::query()->value('status'));
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function makeSettings(array $overrides = []): SiteSetting
    {
        return SiteSetting::query()->create(array_merge([
            'name' => 'RBA',
            'og_site_name' => 'RBA',
            'og_locale' => 'pt_BR',
            'twitter_card' => 'summary_large_image',
            'backup_hour' => 3,
            'backup_retention_days' => 7,
        ], $overrides));
    }

    private function makePage(): Page
    {
        return Page::query()->create([
            'slug' => 'home',
            'template' => 'home',
            'status' => 'published',
            'seo_title' => 'Home',
            'content' => ['hero' => ['subtitle' => 'ok']],
            'published_at' => now(),
        ]);
    }
}

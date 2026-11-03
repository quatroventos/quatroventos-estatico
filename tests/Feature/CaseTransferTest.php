<?php

namespace Tests\Feature;

use App\Models\CaseStudy;
use App\Models\MediaOptimization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CaseTransferTest extends TestCase
{
    use RefreshDatabase;

    private string $archive;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->archive = storage_path('framework/testing/cases-'.uniqid().'.zip');
    }

    protected function tearDown(): void
    {
        File::delete($this->archive);

        parent::tearDown();
    }

    private function makeCase(string $status = 'published'): CaseStudy
    {
        Storage::disk('public')->put('media/cases/tela.png', 'tela');
        Storage::disk('public')->put('optimized/media/cases/tela.webp', 'tela-webp');
        Storage::disk('public')->put('media/cases/capa.webp', 'capa');
        MediaOptimization::query()->create([
            'source_path' => 'media/cases/tela.png',
            'optimized_path' => 'optimized/media/cases/tela.webp',
            'status' => MediaOptimization::STATUS_OPTIMIZED,
        ]);

        $case = CaseStudy::query()->create([
            'slug' => 'fiscal-hub',
            'status' => $status,
            'client' => 'Fiscal Hub',
            'title' => 'Cobrança no WhatsApp',
            'cover_path' => '/storage/media/cases/capa.webp',
            'services' => ['software-sob-medida'],
            'content' => ['sections' => [
                ['id' => 'g', 'type' => 'site.gallery', 'data' => ['items' => [
                    ['image' => '/storage/media/cases/tela.png', 'caption' => 'Painel'],
                    ['image' => 'assets/cases/fiscal.png', 'caption' => 'Versionada'],
                ]]],
            ]],
            'published_at' => now(),
        ]);
        CaseStudy::query()->create([
            'slug' => 'fiscal-hub',
            'locale' => 'es',
            'translation_of' => $case->id,
            'status' => $status,
            'client' => 'Fiscal Hub',
            'title' => 'Cobranza en WhatsApp',
            'cover_path' => '/storage/media/cases/capa.webp',
        ]);

        return $case;
    }

    private function wipe(): void
    {
        CaseStudy::query()->forceDelete();
        MediaOptimization::query()->delete();
        Storage::disk('public')->deleteDirectory('media');
        Storage::disk('public')->deleteDirectory('optimized');
    }

    public function test_round_trip_recreates_case_translation_media_and_optimization_as_draft(): void
    {
        $this->makeCase();
        $this->artisan('cases:export', ['--output' => $this->archive])->assertSuccessful();
        $this->wipe();

        $this->artisan('cases:import', ['file' => $this->archive])->assertSuccessful();

        $case = CaseStudy::query()->whereNull('translation_of')->where('slug', 'fiscal-hub')->firstOrFail();
        $this->assertSame('draft', $case->status);
        $this->assertSame('/storage/media/cases/capa.webp', $case->cover_path);
        $this->assertSame(['software-sob-medida'], $case->services);
        $this->assertSame('assets/cases/fiscal.png', $case->sections()[0]['data']['items'][1]['image']);
        $this->assertSame('Cobranza en WhatsApp', $case->translations()->where('locale', 'es')->value('title'));
        Storage::disk('public')->assertExists(['media/cases/tela.png', 'media/cases/capa.webp', 'optimized/media/cases/tela.webp']);
        $this->assertSame('optimized/media/cases/tela.webp', MediaOptimization::query()->where('source_path', 'media/cases/tela.png')->value('optimized_path'));
    }

    public function test_dry_run_writes_nothing(): void
    {
        $this->makeCase();
        $this->artisan('cases:export', ['--output' => $this->archive])->assertSuccessful();
        $this->wipe();

        $this->artisan('cases:import', ['file' => $this->archive, '--dry-run' => true])->assertSuccessful();

        $this->assertSame(0, CaseStudy::withTrashed()->count());
        Storage::disk('public')->assertMissing('media/cases/tela.png');
    }

    public function test_existing_case_keeps_status_and_publish_flag_publishes(): void
    {
        $this->makeCase('published');
        $this->artisan('cases:export', ['--output' => $this->archive])->assertSuccessful();

        $this->artisan('cases:import', ['file' => $this->archive])->assertSuccessful();
        $this->assertSame('published', CaseStudy::query()->whereNull('translation_of')->value('status'));
        $this->assertSame(2, CaseStudy::query()->count());

        CaseStudy::query()->update(['status' => 'draft']);
        $this->artisan('cases:import', ['file' => $this->archive, '--publish' => true])->assertSuccessful();
        $this->assertSame(['published'], CaseStudy::query()->pluck('status')->unique()->values()->all());
    }

    public function test_same_name_with_other_content_is_imported_under_a_new_name(): void
    {
        $this->makeCase();
        $this->artisan('cases:export', ['--output' => $this->archive])->assertSuccessful();
        $this->wipe();
        Storage::disk('public')->put('media/cases/capa.webp', 'outra imagem');

        $this->artisan('cases:import', ['file' => $this->archive])->assertSuccessful();

        $cover = (string) CaseStudy::query()->whereNull('translation_of')->value('cover_path');
        $this->assertNotSame('/storage/media/cases/capa.webp', $cover);
        $this->assertMatchesRegularExpression('#^/storage/media/cases/capa-[a-z0-9]{6}\.webp$#', $cover);
        $this->assertSame('capa', Storage::disk('public')->get(substr($cover, strlen('/storage/'))));
        $this->assertSame('outra imagem', Storage::disk('public')->get('media/cases/capa.webp'));
        $this->assertSame($cover, CaseStudy::query()->where('locale', 'es')->value('cover_path'));
    }

    public function test_import_rejects_files_that_are_not_case_exports(): void
    {
        File::ensureDirectoryExists(dirname($this->archive));
        $zip = new \ZipArchive;
        $zip->open($this->archive, \ZipArchive::CREATE);
        $zip->addFromString('manifest.json', '{"format":"outro"}');
        $zip->close();

        $this->artisan('cases:import', ['file' => $this->archive])
            ->expectsOutputToContain('não é uma exportação de cases')
            ->assertFailed();
    }
}

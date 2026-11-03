<?php

namespace Tests\Feature;

use App\Cms\SectionCatalog;
use App\Services\SectionThumbnailCapture;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class SectionThumbnailLibraryTest extends TestCase
{
    private const PREVIEW = 'cases-list';

    private bool $existed = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->existed = is_file(SectionThumbnailCapture::filePath(self::PREVIEW));

        if ($this->existed) {
            File::move(SectionThumbnailCapture::filePath(self::PREVIEW), SectionThumbnailCapture::filePath(self::PREVIEW).'.bak');
        }
    }

    protected function tearDown(): void
    {
        File::delete(SectionThumbnailCapture::filePath(self::PREVIEW));

        if ($this->existed) {
            File::move(SectionThumbnailCapture::filePath(self::PREVIEW).'.bak', SectionThumbnailCapture::filePath(self::PREVIEW));
        }

        parent::tearDown();
    }

    public function test_library_item_exposes_thumbnail_only_when_the_image_exists(): void
    {
        $this->assertNull($this->libraryItem()['thumbnail']);

        File::ensureDirectoryExists(dirname(SectionThumbnailCapture::filePath(self::PREVIEW)));
        File::put(SectionThumbnailCapture::filePath(self::PREVIEW), 'webp');

        $this->assertStringStartsWith('/assets/sections/cases-list.webp?v=', (string) $this->libraryItem()['thumbnail']);
    }

    /**
     * @return array<string, mixed>
     */
    private function libraryItem(): array
    {
        return collect(SectionCatalog::library()[0]['items'])->firstWhere('preview', self::PREVIEW);
    }
}

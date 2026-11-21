<?php

namespace App\Cms;

use App\Models\AiVisibilityReport;
use App\Models\Page;
use App\Support\Locales;
use Illuminate\Support\Facades\File;

class EnsureAiVisibilityCmsPage
{
    public static function apply(bool $force = false): ?Page
    {
        $file = database_path('data/pages/como-aparecer-no-chatgpt.json');
        if (! File::exists($file)) {
            return null;
        }

        $page = json_decode(File::get($file), true, flags: JSON_THROW_ON_ERROR);
        $content = PageContentMigrator::normalize(['sections' => $page['sections'] ?? []], $page['template'] ?? 'custom');

        $row = Page::query()->firstOrNew([
            'slug' => AiVisibilityReport::PATH,
            'locale' => Locales::default(),
        ]);

        if ($row->exists && ! $force) {
            return $row;
        }

        $row->fill([
            'template' => $page['template'] ?? 'custom',
            'status' => 'published',
            'seo_title' => $page['seo_title'] ?? null,
            'seo_description' => $page['seo_description'] ?? null,
            'canonical' => $page['canonical'] ?? null,
            'og_image' => $page['og_image'] ?? null,
            'og_image_alt' => $page['og_image_alt'] ?? null,
            'robots' => $page['robots'] ?? 'index, follow, max-image-preview:large',
            'twitter_card' => $page['twitter_card'] ?? 'summary_large_image',
            'content' => $content,
        ]);

        if ($row->published_at === null) {
            $row->published_at = now();
        }

        $row->save();

        return $row;
    }
}

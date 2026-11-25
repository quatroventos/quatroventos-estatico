<?php

use App\Models\Page;
use App\Support\Locales;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    /**
     * @var array<string, array{monthly: string, annual: string, triennial: string}>
     */
    private const SITES_PRICES = [
        'Basic' => ['monthly' => '45', 'annual' => '450', 'triennial' => '1076.40'],
        'Pro' => ['monthly' => '75', 'annual' => '750', 'triennial' => '1616.40'],
        'Ultra' => ['monthly' => '95', 'annual' => '950', 'triennial' => '2156.40'],
    ];

    public function up(): void
    {
        config(['cms.og_on_save' => false]);

        $page = Page::query()->firstOrNew([
            'slug' => 'hospedagem-de-sites',
            'locale' => Locales::default(),
        ]);

        if (! $page->exists) {
            $this->seedFromJson($page);

            return;
        }

        $content = $page->content;
        if (! is_array($content)) {
            $content = [];
        }

        $sections = $content['sections'] ?? [];
        if (! is_array($sections) || $sections === []) {
            $this->seedFromJson($page);

            return;
        }

        $changed = false;

        foreach ($sections as &$section) {
            if (($section['type'] ?? null) !== 'site.pricing') {
                continue;
            }

            $categories = $section['data']['categories'] ?? [];
            if (! is_array($categories)) {
                continue;
            }

            foreach ($categories as &$category) {
                if (($category['id'] ?? null) !== 'sites') {
                    continue;
                }

                $plans = $category['plans'] ?? [];
                if (! is_array($plans)) {
                    continue;
                }

                foreach ($plans as &$plan) {
                    $name = (string) ($plan['name'] ?? '');
                    $prices = self::SITES_PRICES[$name] ?? null;
                    if ($prices === null) {
                        continue;
                    }

                    foreach ($prices as $key => $value) {
                        if (($plan[$key] ?? null) !== $value) {
                            $plan[$key] = $value;
                            $changed = true;
                        }
                    }
                }
                unset($plan);

                $category['plans'] = $plans;
            }
            unset($category);

            $section['data']['categories'] = $categories;
        }
        unset($section);

        if (! $changed) {
            return;
        }

        $page->revisions()->create([
            'snapshot' => $page->revisionSnapshot(),
            'created_at' => now(),
        ]);

        $content['sections'] = $sections;
        $page->content = $content;
        $page->save();
    }

    private function seedFromJson(Page $page): void
    {
        $path = database_path('data/pages/hospedagem-de-sites.json');
        if (! File::exists($path)) {
            return;
        }

        $data = json_decode(File::get($path), true, flags: JSON_THROW_ON_ERROR);

        if ($page->exists) {
            $page->revisions()->create([
                'snapshot' => $page->revisionSnapshot(),
                'created_at' => now(),
            ]);
        }

        $page->forceFill([
            'template' => $data['template'] ?? 'custom',
            'status' => $data['status'] ?? 'published',
            'published_at' => $page->published_at ?? now(),
            'seo_title' => $data['seo_title'] ?? $page->seo_title,
            'seo_description' => $data['seo_description'] ?? $page->seo_description,
            'robots' => $data['robots'] ?? $page->robots,
            'twitter_card' => $data['twitter_card'] ?? $page->twitter_card,
            'og_image' => $data['og_image'] ?? $page->og_image,
            'og_image_alt' => $data['og_image_alt'] ?? $page->og_image_alt,
            'content' => ['sections' => $data['sections'] ?? []],
        ])->save();
    }

    public function down(): void
    {
        //
    }
};

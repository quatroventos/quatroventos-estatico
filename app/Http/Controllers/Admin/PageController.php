<?php

namespace App\Http\Controllers\Admin;

use App\Cms\PageContentMigrator;
use App\Cms\SectionCatalog;
use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\PageRevision;
use App\Models\SiteSetting;
use App\Support\Locales;
use App\Support\Seo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Page::class);

        $pages = Page::query()->originals()->with('translations')->orderedByMenu()->get()
            ->map(fn (Page $page) => [...$this->summary($page), 'translations' => $this->translationStatus($page)]);

        return response()->json(['data' => $pages]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Page::class);

        $data = $request->validate([
            'seo_title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:120',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('pages', 'slug')->where('locale', Locales::default()),
                Rule::notIn(Page::reservedSlugs()),
            ],
        ], $this->slugMessages());

        $page = Page::query()->create([
            'slug' => $data['slug'],
            'template' => 'custom',
            'status' => 'published',
            'published_at' => now(),
            'seo_title' => $data['seo_title'],
            'seo_description' => null,
            'content' => ['sections' => []],
        ]);

        return response()->json(['data' => $this->detail($page)], 201);
    }

    public function show(Page $page): JsonResponse
    {
        $this->authorize('view', $page);

        $site = SiteSetting::query()->first();
        $normalized = PageContentMigrator::normalize($page->content, $page->template);
        $sections = $normalized['sections'];

        return response()->json([
            'data' => $this->detail($page, $normalized),
            'schema' => SectionCatalog::schemaForSections($sections),
            'library' => SectionCatalog::library(),
            'seo' => Seo::defaults($site),
        ]);
    }

    public function update(Request $request, Page $page): JsonResponse
    {
        $this->authorize('update', $page);

        $data = $request->validate([
            'slug' => [
                'sometimes',
                'string',
                'max:120',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('pages', 'slug')->where('locale', $page->localeCode())->ignore($page->id),
                Rule::notIn(array_values(array_diff(Page::reservedSlugs(), [$page->slug]))),
            ],
            'seo_title' => ['required', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'canonical' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'string', 'max:500'],
            'og_image_alt' => ['nullable', 'string', 'max:255'],
            'robots' => ['nullable', 'string', 'max:255'],
            'twitter_card' => ['nullable', 'string', 'max:64'],
            'status' => ['sometimes', Rule::in(['draft', 'published'])],
            'content' => ['required', 'array'],
            'content.sections' => ['required', 'array'],
            'content.sections.*.id' => ['required', 'string', 'max:40'],
            'content.sections.*.type' => ['required', 'string', Rule::in(SectionCatalog::types())],
            'content.sections.*.data' => ['nullable', 'array'],
        ], $this->slugMessages());

        if ($page->isHome()) {
            unset($data['slug']);
        }

        if ($page->isOriginal()) {
            $data['status'] = 'published';
        } else {
            $data['status'] ??= $page->status;
        }

        if ($data['status'] === 'published' && $page->published_at === null) {
            $data['published_at'] = now();
        }

        $data['content'] = PageContentMigrator::normalize($data['content'], $page->template);

        if ($this->pageChanged($page, $data)) {
            $page->revisions()->create([
                'user_id' => $request->user()?->id,
                'snapshot' => $page->revisionSnapshot(),
                'created_at' => now(),
            ]);
        }

        $page->update($data);

        $fresh = $page->fresh();
        $normalized = PageContentMigrator::normalize($fresh?->content, $fresh?->template);

        return response()->json([
            'data' => $this->detail($fresh, $normalized),
            'schema' => SectionCatalog::schemaForSections($normalized['sections']),
        ]);
    }

    public function destroy(Page $page): JsonResponse
    {
        $this->authorize('delete', $page);

        abort_if($page->isHome(), 422, 'A home não pode ser excluída.');

        MenuItem::query()->where('page_id', $page->id)->delete();
        $page->delete();

        return response()->json(['ok' => true]);
    }

    public function revisions(Page $page): JsonResponse
    {
        $this->authorize('view', $page);

        $items = $page->revisions()
            ->with('user:id,name')
            ->latest('id')
            ->get()
            ->map(fn (PageRevision $revision) => $this->revisionSummary($revision));

        return response()->json(['data' => $items]);
    }

    public function restoreRevision(Request $request, Page $page, PageRevision $revision): JsonResponse
    {
        $this->authorize('update', $page);

        abort_unless($revision->page_id === $page->id, 404);

        $snapshot = $revision->snapshot ?? [];

        $page->revisions()->create([
            'user_id' => $request->user()?->id,
            'snapshot' => $page->revisionSnapshot(),
            'created_at' => now(),
        ]);

        $content = is_array($snapshot['content'] ?? null) ? $snapshot['content'] : [];
        $content = PageContentMigrator::normalize($content, $page->template);

        $payload = [
            'status' => $page->isOriginal() ? 'published' : $page->status,
            'seo_title' => $snapshot['seo_title'] ?? $page->seo_title,
            'seo_description' => $snapshot['seo_description'] ?? null,
            'canonical' => $snapshot['canonical'] ?? null,
            'og_image' => $snapshot['og_image'] ?? null,
            'og_image_alt' => $snapshot['og_image_alt'] ?? null,
            'robots' => $snapshot['robots'] ?? null,
            'twitter_card' => $snapshot['twitter_card'] ?? null,
            'content' => $content,
        ];

        if (! $page->isHome() && is_string($snapshot['slug'] ?? null) && $snapshot['slug'] !== '') {
            $payload['slug'] = $snapshot['slug'];
        }

        $payload['published_at'] = $page->published_at ?? now();

        $page->update($payload);

        return response()->json(['data' => $this->detail($page->fresh())]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function pageChanged(Page $page, array $data): bool
    {
        foreach (['status', 'slug', 'seo_title', 'seo_description', 'canonical', 'og_image', 'og_image_alt', 'robots', 'twitter_card', 'content'] as $field) {
            if (! array_key_exists($field, $data)) {
                continue;
            }

            if ($field === 'content') {
                if (json_encode($page->content ?? []) !== json_encode($data['content'])) {
                    return true;
                }

                continue;
            }

            if ((string) ($page->{$field} ?? '') !== (string) ($data[$field] ?? '')) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<string, mixed>
     */
    private function revisionSummary(PageRevision $revision): array
    {
        $snapshot = $revision->snapshot ?? [];

        return [
            'id' => $revision->id,
            'created_at' => $revision->created_at?->toIso8601String(),
            'user_name' => $revision->user?->name,
            'status' => $snapshot['status'] ?? null,
            'seo_title' => $snapshot['seo_title'] ?? null,
        ];
    }

    /**
     * @return array<string, string>
     */
    private function slugMessages(): array
    {
        return [
            'seo_title.required' => 'Informe o título.',
            'slug.required' => 'Informe o endereço da página.',
            'slug.regex' => 'Use apenas letras minúsculas, números e hífens.',
            'slug.unique' => 'Já existe uma página com este endereço.',
            'slug.not_in' => 'Este endereço está reservado pelo sistema.',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function summary(Page $page): array
    {
        return [
            'id' => $page->id,
            'slug' => $page->slug,
            'locale' => $page->localeCode(),
            'translation_of' => $page->translation_of,
            'path' => $page->publicPath(),
            'template' => $page->template,
            'status' => $page->status,
            'seo_title' => $page->seo_title,
            'updated_at' => $page->updated_at?->toIso8601String(),
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function translationStatus(Page $page): array
    {
        $rows = $page->relationLoaded('translations') ? $page->translations->keyBy('locale') : $page->translations()->get()->keyBy('locale');
        $hash = $page->sourceHash();
        $status = [];

        foreach (Locales::translated() as $locale) {
            $row = $rows->get($locale);
            $status[$locale] = [
                'id' => $row?->id,
                'status' => $row === null ? 'missing' : (string) $row->status,
                'stale' => $row !== null && $row->source_hash !== null && $row->source_hash !== $hash,
                'slug' => $row?->slug,
                'translated_at' => $row?->translated_at?->toIso8601String(),
            ];
        }

        return $status;
    }

    /**
     * @param  array{sections: list<array<string, mixed>>}|null  $content
     * @return array<string, mixed>
     */
    private function detail(Page $page, ?array $content = null): array
    {
        $normalized = $content ?? PageContentMigrator::normalize($page->content, $page->template);

        return [
            ...$this->summary($page),
            'seo_description' => $page->seo_description,
            'canonical' => $page->canonical,
            'og_image' => $page->og_image,
            'og_image_alt' => $page->og_image_alt,
            'robots' => $page->robots,
            'twitter_card' => $page->twitter_card,
            'published_at' => $page->published_at?->toIso8601String(),
            'content' => $normalized,
            'source_id' => $page->sourceRow()->id,
            'source_slug' => $page->sourceRow()->slug,
            'stale' => $page->isTranslationStale(),
            'translated_at' => $page->translated_at?->toIso8601String(),
            'translations' => $this->translationStatus($page->sourceRow()),
        ];
    }
}

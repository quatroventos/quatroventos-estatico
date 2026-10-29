<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\TranslateContentJob;
use App\Models\CaseStudy;
use App\Models\MenuItem;
use App\Models\Page;
use App\Services\Translation\TextTranslator;
use App\Support\Locales;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TranslationController extends Controller
{
    public function config(TextTranslator $translator): JsonResponse
    {
        return response()->json([
            'configured' => $translator->configured(),
            'default' => Locales::default(),
            'locales' => array_values(Locales::publicList()),
            'translated' => Locales::translated(),
        ]);
    }

    public function page(Request $request, Page $page, string $locale, TextTranslator $translator): JsonResponse
    {
        $source = $page->sourceRow();
        $this->authorize('update', $source);
        $this->guard($locale, $translator);

        TranslateContentJob::queue('page', $source->id, $locale, $request->user()?->id);

        return $this->status('page', $source->id, $locale);
    }

    public function caseStudy(Request $request, CaseStudy $caseStudy, string $locale, TextTranslator $translator): JsonResponse
    {
        $source = $caseStudy->sourceRow();
        $this->authorize('update', $source);
        $this->guard($locale, $translator);

        TranslateContentJob::queue('case', $source->id, $locale, $request->user()?->id);

        return $this->status('case', $source->id, $locale);
    }

    public function menus(Request $request, string $locale, TextTranslator $translator): JsonResponse
    {
        $this->authorize('create', MenuItem::class);
        $this->guard($locale, $translator);

        TranslateContentJob::queue('menus', null, $locale, $request->user()?->id);

        return $this->status('menus', null, $locale);
    }

    public function show(Request $request): JsonResponse
    {
        $data = $request->validate([
            'kind' => ['required', Rule::in(['page', 'case', 'menus'])],
            'id' => ['nullable', 'integer'],
            'locale' => ['required', Rule::in(Locales::translated())],
        ]);

        $id = isset($data['id']) ? (int) $data['id'] : null;

        if ($data['kind'] === 'page' && $id !== null) {
            $this->authorize('view', Page::query()->findOrFail($id));
        } elseif ($data['kind'] === 'case' && $id !== null) {
            $this->authorize('view', CaseStudy::query()->findOrFail($id));
        } else {
            $this->authorize('viewAny', MenuItem::class);
        }

        return $this->status($data['kind'], $id, $data['locale']);
    }

    private function status(string $kind, ?int $id, string $locale): JsonResponse
    {
        $state = TranslateContentJob::status($kind, $id, $locale);
        $row = match ($kind) {
            'page' => $id ? Page::query()->where('translation_of', $id)->where('locale', $locale)->first() : null,
            'case' => $id ? CaseStudy::query()->where('translation_of', $id)->where('locale', $locale)->first() : null,
            default => null,
        };

        return response()->json([
            'state' => $state['state'] ?? 'idle',
            'message' => $state['message'] ?? null,
            'translation' => $row ? [
                'id' => $row->id,
                'slug' => $row->slug,
                'status' => $row->status,
                'path' => $row->publicPath(),
                'translated_at' => $row->translated_at?->toIso8601String(),
            ] : null,
        ]);
    }

    private function guard(string $locale, TextTranslator $translator): void
    {
        abort_unless(in_array($locale, Locales::translated(), true), 404);
        abort_unless($translator->configured(), 422, 'Defina OPENAI_API_KEY no .env para traduzir com IA.');
    }
}

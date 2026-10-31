<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\Seo\SerpBearRanking;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

/**
 * Bloco "Ranking" da aba Desempenho, servido pelo SerpBear.
 */
class RankController extends Controller
{
    public function __construct(private SerpBearRanking $ranking) {}

    public function show(Request $request): JsonResponse
    {
        $this->authorize('view', SiteSetting::current());

        return response()->json($this->ranking->report($request->boolean('fresh')));
    }

    public function refresh(): JsonResponse
    {
        return $this->write(fn () => $this->ranking->refresh());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'keywords' => ['required', 'array', 'min:1', 'max:50'],
            'keywords.*' => ['string', 'max:200'],
        ]);

        $keywords = array_values(array_unique(array_filter(array_map(fn (string $keyword) => trim($keyword), $data['keywords']))));

        return $this->write(fn () => $this->ranking->add($keywords));
    }

    public function destroy(Request $request): JsonResponse
    {
        $data = $request->validate(['ids' => ['required', 'array', 'min:1', 'max:100'], 'ids.*' => ['integer', 'min:1']]);

        return $this->write(fn () => $this->ranking->remove(array_map('intval', $data['ids'])));
    }

    private function write(Closure $callback): JsonResponse
    {
        $this->authorize('update', SiteSetting::current());

        if (! $this->ranking->configured()) {
            return response()->json(['message' => 'SerpBear não configurado. Preencha em SEO > Integrações.'], 422);
        }

        try {
            $callback();
        } catch (RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        }

        return response()->json($this->ranking->report(true));
    }
}

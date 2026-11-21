<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiVisibilityReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiVisibilityReportController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', AiVisibilityReport::class);

        $query = AiVisibilityReport::query()->latest();

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($builder) use ($search): void {
                $builder
                    ->where('host', 'like', '%'.$search.'%')
                    ->orWhere('url', 'like', '%'.$search.'%')
                    ->orWhere('public_id', 'like', '%'.$search.'%');
            });
        }

        $items = $query
            ->limit(200)
            ->get([
                'id',
                'public_id',
                'url',
                'host',
                'score',
                'band',
                'niche',
                'response_ms',
                'ip',
                'created_at',
            ]);

        return response()->json(['data' => $items]);
    }

    public function show(AiVisibilityReport $aiVisibilityReport): JsonResponse
    {
        $this->authorize('view', $aiVisibilityReport);

        return response()->json(['data' => $aiVisibilityReport]);
    }

    public function destroy(AiVisibilityReport $aiVisibilityReport): JsonResponse
    {
        $this->authorize('delete', $aiVisibilityReport);
        $aiVisibilityReport->delete();

        return response()->json([], 204);
    }
}

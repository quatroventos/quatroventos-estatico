<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\RestoresSoftDeletedModels;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    use RestoresSoftDeletedModels;

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Branch::class);

        return response()->json([
            'data' => $this->scopedQuery($request, Branch::class)
                ->orderBy('sort')
                ->orderBy('city')
                ->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Branch::class);

        $branch = Branch::query()->create($this->rules($request));

        return response()->json(['data' => $branch], 201);
    }

    public function update(Request $request, Branch $branch): JsonResponse
    {
        $this->authorize('update', $branch);
        $branch->update($this->rules($request, $branch->id));

        return response()->json(['data' => $branch]);
    }

    public function destroy(Branch $branch): JsonResponse
    {
        $this->authorize('delete', $branch);
        $branch->delete();

        return response()->json([], 204);
    }

    public function restore(Request $request, int $branch): JsonResponse
    {
        return $this->restoreSoftDeleted($request, Branch::class, $branch);
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'slug' => ['required', 'string', 'max:120', 'unique:branches,slug'.($id ? ','.$id : '')],
            'city' => ['required', 'string', 'max:120'],
            'state' => ['required', 'string', 'max:8'],
            'select_label' => ['required', 'string', 'max:255'],
            'eyebrow' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'is_headquarters' => ['sometimes', 'boolean'],
            'sort' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}

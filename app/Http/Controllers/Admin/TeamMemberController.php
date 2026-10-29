<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\RestoresSoftDeletedModels;
use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    use RestoresSoftDeletedModels;

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', TeamMember::class);

        return response()->json([
            'data' => $this->scopedQuery($request, TeamMember::class)
                ->orderBy('sort')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', TeamMember::class);

        $member = TeamMember::query()->create($this->rules($request));

        return response()->json(['data' => $member], 201);
    }

    public function update(Request $request, TeamMember $teamMember): JsonResponse
    {
        $this->authorize('update', $teamMember);

        $teamMember->update($this->rules($request));

        return response()->json(['data' => $teamMember]);
    }

    public function destroy(TeamMember $teamMember): JsonResponse
    {
        $this->authorize('delete', $teamMember);
        $teamMember->delete();

        return response()->json([], 204);
    }

    public function restore(Request $request, int $teamMember): JsonResponse
    {
        return $this->restoreSoftDeleted($request, TeamMember::class, $teamMember);
    }

    /**
     * @return array<string, mixed>
     */
    private function rules(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'photo_path' => ['nullable', 'string', 'max:500'],
            'sort' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}

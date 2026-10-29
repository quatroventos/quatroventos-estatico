<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\RestoresSoftDeletedModels;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use RestoresSoftDeletedModels;

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', User::class);

        $users = $this->scopedQuery($request, User::class)
            ->with('roles')
            ->orderBy('name')
            ->get()
            ->map(fn (User $user) => $this->payload($user));

        return response()->json(['data' => $users]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:root,user'],
        ]);

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
        $user->syncRoles([$data['role']]);

        return response()->json(['data' => $this->payload($user)], 201);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->whereNull('deleted_at')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'in:root,user'],
        ]);

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if (filled($data['password'] ?? null)) {
            $payload['password'] = $data['password'];
        }

        $user->update($payload);
        $user->syncRoles([$data['role']]);

        return response()->json(['data' => $this->payload($user->fresh('roles'))]);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);
        $user->delete();

        return response()->json([], 204);
    }

    public function restore(Request $request, int $user): JsonResponse
    {
        $model = User::onlyTrashed()->findOrFail($user);
        $this->authorize('update', $model);

        $taken = User::query()
            ->where('email', $model->email)
            ->whereKeyNot($model->id)
            ->exists();

        if ($taken) {
            return response()->json([
                'message' => 'Já existe um usuário ativo com este e-mail.',
            ], 422);
        }

        $model->restore();

        return response()->json(['data' => $this->payload($model->fresh('roles'))]);
    }

    public function profile(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        return response()->json(['data' => $this->payload($user)]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $payload = ['name' => $data['name']];
        if (filled($data['password'] ?? null)) {
            $payload['password'] = $data['password'];
        }
        $user->update($payload);

        return response()->json(['data' => $this->payload($user->fresh('roles'))]);
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(User $user): array
    {
        $user->loadMissing('roles');

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->cmsRole(),
        ];
    }
}

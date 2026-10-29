<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public const IMPERSONATOR_KEY = 'cms_impersonator_id';

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()->where('email', $credentials['email'])->first();

        if ($user === null || ! Auth::getProvider()->validateCredentials($user, $credentials)) {
            app(AuditLogger::class)->record(
                $request,
                null,
                'login.failed',
                'Tentativa de login inválida',
                422,
                ['user_email' => $credentials['email']],
            );

            throw ValidationException::withMessages([
                'email' => 'Credenciais inválidas.',
            ]);
        }

        if (! $user->isCmsMember()) {
            app(AuditLogger::class)->record(
                $request,
                $user,
                'login.denied',
                $user->name.' tentou entrar sem permissão',
                422,
            );

            throw ValidationException::withMessages([
                'email' => 'Credenciais inválidas.',
            ]);
        }

        $request->session()->forget(self::IMPERSONATOR_KEY);
        Auth::login($user);
        $request->session()->regenerate();

        app(AuditLogger::class)->record(
            $request,
            $user,
            'login',
            $user->name.' entrou no painel',
            200,
        );

        return response()->json([
            'status' => 'authenticated',
            'user' => $this->payload($request, $user),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['status' => 'logged_out']);
    }

    public function me(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        return response()->json(['user' => $this->payload($request, $user)]);
    }

    public function impersonate(Request $request, User $user): JsonResponse
    {
        /** @var User $actor */
        $actor = $request->user();

        $this->authorize('impersonate', $user);

        if ($request->session()->has(self::IMPERSONATOR_KEY)) {
            throw ValidationException::withMessages([
                'user' => 'Já está acessando como outro usuário.',
            ]);
        }

        if ($actor->is($user)) {
            throw ValidationException::withMessages([
                'user' => 'Não é possível acessar como você mesmo.',
            ]);
        }

        if (! $user->isCmsMember()) {
            throw ValidationException::withMessages([
                'user' => 'Este usuário não tem acesso ao painel.',
            ]);
        }

        $request->session()->put(self::IMPERSONATOR_KEY, $actor->id);
        Auth::login($user);
        $request->session()->regenerate();

        app(AuditLogger::class)->record(
            $request,
            $actor,
            'impersonate',
            $actor->name.' acessou como '.$user->name,
            200,
            [
                'target_id' => $user->id,
                'target_email' => $user->email,
            ],
        );

        return response()->json([
            'status' => 'impersonating',
            'user' => $this->payload($request, $user),
        ]);
    }

    public function leaveImpersonation(Request $request): JsonResponse
    {
        $impersonatorId = $request->session()->pull(self::IMPERSONATOR_KEY);

        if ($impersonatorId === null) {
            throw ValidationException::withMessages([
                'user' => 'Nenhuma impersonação ativa.',
            ]);
        }

        /** @var User|null $current */
        $current = $request->user();
        $root = User::query()->find($impersonatorId);

        if ($root === null || ! $root->isRoot()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'user' => 'Sessão de impersonação inválida.',
            ]);
        }

        Auth::login($root);
        $request->session()->regenerate();

        app(AuditLogger::class)->record(
            $request,
            $root,
            'impersonate.leave',
            $root->name.' voltou de '.$current?->name,
            200,
            [
                'target_id' => $current?->id,
                'target_email' => $current?->email,
            ],
        );

        return response()->json([
            'status' => 'restored',
            'user' => $this->payload($request, $root),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(Request $request, User $user): array
    {
        return self::userPayload($request, $user);
    }

    /**
     * @return array<string, mixed>
     */
    public static function userPayload(Request $request, User $user): array
    {
        $impersonatorId = $request->session()->get(self::IMPERSONATOR_KEY);
        $impersonator = $impersonatorId
            ? User::query()->find($impersonatorId)
            : null;

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->cmsRole(),
            'abilities' => $user->cmsAbilities(),
            'tour_progress' => $user->tour_progress ?? [],
            'impersonating' => $impersonator !== null,
            'impersonator' => $impersonator ? [
                'id' => $impersonator->id,
                'name' => $impersonator->name,
                'email' => $impersonator->email,
            ] : null,
        ];
    }
}

<?php

namespace App\Http\Middleware;

use App\Services\AuditLogger;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCmsUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null || ! $user->isCmsMember()) {
            return response()->json(['message' => 'Não autenticado.'], 401);
        }

        $response = $next($request);

        app(AuditLogger::class)->fromRequest($request, $response, $user);

        return $response;
    }
}

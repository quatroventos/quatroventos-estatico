<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirects 301 cadastrados no painel. Roda antes do roteamento, então vale para
 * qualquer caminho público, inclusive os que não casam com nenhuma rota.
 */
class ApplyRedirects
{
    private const SKIP = ['api', 'storage', 'build', 'assets', 't'];

    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            return $next($request);
        }

        $first = explode('/', trim($request->path(), '/'))[0];
        if (in_array($first, [...self::SKIP, cms_admin_path()], true)) {
            return $next($request);
        }

        $source = Redirect::normalizePath($request->getPathInfo());
        $target = Redirect::targetFor($source);

        if ($target === null) {
            return $next($request);
        }

        Redirect::recordHit($source);

        $query = $request->getQueryString();
        if ($query && ! str_contains($target, '?')) {
            $target .= '?'.$query;
        }

        return redirect($target, 301);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pedidos no host com ou sem "www" que não é o do APP_URL vão em 301 para o host oficial.
 * Já aplica config/redirects.php, para o slug antigo chegar ao destino em um salto só.
 */
class RedirectToCanonicalHost
{
    public function handle(Request $request, Closure $next): Response
    {
        $canonical = parse_url((string) config('app.url'));
        $canonicalHost = strtolower((string) ($canonical['host'] ?? ''));
        $host = strtolower($request->getHost());

        if ($canonicalHost === '' || $host === $canonicalHost || ! $this->isWwwVariant($host, $canonicalHost)) {
            return $next($request);
        }

        $query = $request->getQueryString();

        return redirect()->away(
            rtrim((string) config('app.url'), '/').$this->targetPath($request->getPathInfo()).($query ? '?'.$query : ''),
            301
        );
    }

    private function targetPath(string $pathInfo): string
    {
        $path = '/'.trim($pathInfo, '/');
        $lookup = preg_replace('/\.php$/', '', $path) ?? $path;
        $redirects = config('redirects');

        if (is_array($redirects) && is_string($redirects[$lookup] ?? null)) {
            return $redirects[$lookup];
        }

        return $lookup === '/index' ? '/' : $path;
    }

    private function isWwwVariant(string $host, string $canonicalHost): bool
    {
        return $host === 'www.'.$canonicalHost || 'www.'.$host === $canonicalHost;
    }
}

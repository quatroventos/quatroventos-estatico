<?php

namespace App\Http\Middleware;

use App\Http\Concerns\NegotiatesMarkdown;
use App\Services\Seo\PageMarkdown;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Negocia text/markdown nas respostas HTML públicas (padrão acceptmarkdown.com).
 */
class NegotiateMarkdown
{
    use NegotiatesMarkdown;

    public function __construct(private PageMarkdown $markdown) {}

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->isMethodSafe()) {
            return $response;
        }

        $contentType = strtolower((string) $response->headers->get('Content-Type', ''));
        $isHtml = str_contains($contentType, 'text/html')
            || ($contentType === '' && is_string($response->getContent()) && preg_match('#^\s*<(!doctype|html)\b#i', $response->getContent()) === 1);

        if (! $isHtml) {
            return $response;
        }

        $this->appendVaryAccept($response);

        if (! $this->prefersMarkdown($request)) {
            return $response;
        }

        $html = (string) $response->getContent();
        $body = $this->markdown->fromHtml($html);

        if (mb_strlen(trim($body)) < 20) {
            return $response;
        }

        $response->setContent($body);
        $response->headers->set('Content-Type', 'text/markdown; charset=utf-8');
        $response->headers->remove('Content-Length');

        return $response;
    }
}

<?php

namespace App\Http\Concerns;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

trait NegotiatesMarkdown
{
    public function prefersMarkdown(Request $request): bool
    {
        return $this->pickType($request, ['text/html', 'text/markdown']) === 'text/markdown';
    }

    /**
     * @param  list<string>  $produces
     */
    public function pickType(Request $request, array $produces): ?string
    {
        $accept = $request->header('Accept');
        if (! is_string($accept) || trim($accept) === '') {
            return $produces[0] ?? null;
        }

        $entries = [];
        foreach (explode(',', $accept) as $raw) {
            $parts = array_map('trim', explode(';', trim($raw)));
            $type = strtolower((string) array_shift($parts));
            if ($type === '') {
                continue;
            }

            $q = 1.0;
            foreach ($parts as $param) {
                [$name, $value] = array_pad(array_map('trim', explode('=', $param, 2)), 2, '');
                if ($name === 'q' && is_numeric($value)) {
                    $q = max(0.0, min(1.0, (float) $value));
                }
            }

            $specificity = $type === '*/*' ? 0 : (str_ends_with($type, '/*') ? 1 : 2);
            $entries[] = compact('type', 'q', 'specificity');
        }

        $best = null;
        $bestQ = -1.0;
        $bestSpecificity = -1;
        $bestPosition = PHP_INT_MAX;

        foreach ($produces as $candidate) {
            $matched = null;
            $matchedPosition = PHP_INT_MAX;

            foreach ($entries as $index => $entry) {
                $isMatch = $entry['type'] === '*/*'
                    || (str_ends_with($entry['type'], '/*') && str_starts_with($candidate, substr($entry['type'], 0, -1)))
                    || $entry['type'] === $candidate;

                if (! $isMatch) {
                    continue;
                }

                if ($matched === null
                    || $entry['specificity'] > $matched['specificity']
                    || ($entry['specificity'] === $matched['specificity'] && $index < $matchedPosition)
                ) {
                    $matched = $entry;
                    $matchedPosition = $index;
                }
            }

            if ($matched === null || $matched['q'] <= 0) {
                continue;
            }

            if ($matched['q'] > $bestQ
                || ($matched['q'] === $bestQ && $matched['specificity'] > $bestSpecificity)
                || ($matched['q'] === $bestQ && $matched['specificity'] === $bestSpecificity && $matchedPosition < $bestPosition)
            ) {
                $bestQ = $matched['q'];
                $bestSpecificity = $matched['specificity'];
                $bestPosition = $matchedPosition;
                $best = $candidate;
            }
        }

        return $best;
    }

    public function appendVaryAccept(Response $response): void
    {
        $existing = $response->headers->get('Vary');
        if (! is_string($existing) || trim($existing) === '') {
            $response->headers->set('Vary', 'Accept');

            return;
        }

        $tokens = array_map(
            fn (string $token): string => strtolower(trim($token)),
            explode(',', $existing)
        );

        if (! in_array('accept', $tokens, true)) {
            $response->headers->set('Vary', $existing.', Accept');
        }
    }

    public function markdownResponse(string $markdown, int $status = 200): Response
    {
        $response = response($markdown, $status, [
            'Content-Type' => 'text/markdown; charset=utf-8',
        ]);
        $this->appendVaryAccept($response);

        return $response;
    }
}

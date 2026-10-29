<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class LighthouseService
{
    public const CACHE_KEY = 'lighthouse:snapshot';

    /**
     * @return array{
     *     url: string,
     *     status: 'ready'|'pending'|'error',
     *     fetched_at: ?string,
     *     mobile: ?array<string, mixed>,
     *     desktop: ?array<string, mixed>
     * }
     */
    public function snapshot(bool $refreshIfStale = true): array
    {
        $cached = Cache::get(self::CACHE_KEY);

        if (is_array($cached)) {
            if ($refreshIfStale && $this->isStale($cached)) {
                $this->queueRefresh();
            }

            return $cached;
        }

        if ($this->isLocalUrl($this->targetUrl())) {
            return $this->empty('pending');
        }

        if ($refreshIfStale) {
            $this->queueRefresh();
        }

        return $this->empty('pending');
    }

    /**
     * @return array{
     *     url: string,
     *     status: 'ready'|'pending'|'error',
     *     fetched_at: ?string,
     *     mobile: ?array<string, mixed>,
     *     desktop: ?array<string, mixed>
     * }
     */
    public function refresh(): array
    {
        $url = $this->targetUrl();

        if ($this->isLocalUrl($url)) {
            $snapshot = $this->empty('pending');
            Cache::put(self::CACHE_KEY, $snapshot, now()->addDay());

            return $snapshot;
        }

        try {
            $snapshot = [
                'url' => $url,
                'status' => 'ready',
                'fetched_at' => now()->toIso8601String(),
                'mobile' => $this->fetchStrategy($url, 'mobile'),
                'desktop' => $this->fetchStrategy($url, 'desktop'),
            ];
        } catch (Throwable $e) {
            report($e);

            $snapshot = $this->empty('error');
        }

        Cache::put(self::CACHE_KEY, $snapshot, now()->addDay());

        return $snapshot;
    }

    /**
     * @return array<string, mixed>
     */
    private function fetchStrategy(string $url, string $strategy): array
    {
        $query = http_build_query([
            'url' => $url,
            'strategy' => $strategy,
            'locale' => 'pt_BR',
        ]);

        foreach (['PERFORMANCE', 'ACCESSIBILITY', 'BEST_PRACTICES', 'SEO'] as $category) {
            $query .= '&category='.urlencode($category);
        }

        $key = (string) config('cms.pagespeed_key');

        if ($key !== '') {
            $query .= '&key='.urlencode($key);
        }

        $response = Http::timeout(90)
            ->acceptJson()
            ->get('https://www.googleapis.com/pagespeedonline/v5/runPagespeed?'.$query)
            ->throw();

        $lighthouse = $response->json('lighthouseResult') ?? [];
        $categories = $lighthouse['categories'] ?? [];
        $audits = $lighthouse['audits'] ?? [];

        return [
            'performance' => $this->score($categories['performance']['score'] ?? null),
            'accessibility' => $this->score($categories['accessibility']['score'] ?? null),
            'best_practices' => $this->score($categories['best-practices']['score'] ?? null),
            'seo' => $this->score($categories['seo']['score'] ?? null),
            'metrics' => [
                'fcp_ms' => $this->metric($audits['first-contentful-paint']['numericValue'] ?? null),
                'lcp_ms' => $this->metric($audits['largest-contentful-paint']['numericValue'] ?? null),
                'tbt_ms' => $this->metric($audits['total-blocking-time']['numericValue'] ?? null),
                'si_ms' => $this->metric($audits['speed-index']['numericValue'] ?? null),
                'cls' => isset($audits['cumulative-layout-shift']['numericValue'])
                    ? round((float) $audits['cumulative-layout-shift']['numericValue'], 3)
                    : null,
            ],
        ];
    }

    private function queueRefresh(): void
    {
        if (app()->environment('testing')) {
            return;
        }

        dispatch(function (): void {
            Cache::lock('lighthouse:refresh', 120)->get(fn () => $this->refresh());
        })->afterResponse();
    }

    /**
     * @param  array<string, mixed>  $snapshot
     */
    private function isStale(array $snapshot): bool
    {
        if (($snapshot['status'] ?? null) === 'pending' && $this->isLocalUrl($this->targetUrl())) {
            return false;
        }

        $fetchedAt = $snapshot['fetched_at'] ?? null;

        if (! is_string($fetchedAt) || $fetchedAt === '') {
            return true;
        }

        return now()->parse($fetchedAt)->lt(now()->subHours(12));
    }

    private function targetUrl(): string
    {
        $url = trim((string) config('cms.lighthouse_url'));

        return $url !== '' ? $url : (string) config('app.url');
    }

    private function isLocalUrl(string $url): bool
    {
        $host = parse_url($url, PHP_URL_HOST);

        if (! is_string($host) || $host === '') {
            return true;
        }

        return in_array($host, ['localhost', '127.0.0.1', '::1'], true)
            || str_ends_with($host, '.local')
            || str_ends_with($host, '.test');
    }

    private function score(mixed $value): ?int
    {
        if (! is_numeric($value)) {
            return null;
        }

        return (int) round(((float) $value) * 100);
    }

    private function metric(mixed $value): ?int
    {
        if (! is_numeric($value)) {
            return null;
        }

        return (int) round((float) $value);
    }

    /**
     * @return array{
     *     url: string,
     *     status: 'ready'|'pending'|'error',
     *     fetched_at: ?string,
     *     mobile: ?array<string, mixed>,
     *     desktop: ?array<string, mixed>
     * }
     */
    private function empty(string $status): array
    {
        return [
            'url' => $this->targetUrl(),
            'status' => $status,
            'fetched_at' => null,
            'mobile' => null,
            'desktop' => null,
        ];
    }
}

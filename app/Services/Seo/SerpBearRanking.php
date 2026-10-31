<?php

namespace App\Services\Seo;

use Illuminate\Support\Facades\Cache;
use RuntimeException;

/**
 * Bloco "Ranking" da aba Desempenho, com posições do SerpBear (sem custo por consulta,
 * só a cota do scraper configurado nele). Palavras iguais em desktop e celular viram uma linha.
 */
class SerpBearRanking
{
    public const READ_TTL_MINUTES = 5;

    public const DEVICES = ['desktop', 'mobile'];

    public function __construct(private SerpBearClient $client) {}

    public function configured(): bool
    {
        return $this->client->configured();
    }

    /**
     * @return array<string, mixed>
     */
    public function report(bool $fresh = false): array
    {
        if (! $this->configured()) {
            return ['state' => 'unconfigured', 'message' => 'SerpBear não configurado. Preencha URL, chave de API e domínio em SEO > Integrações.'];
        }

        $key = $this->cacheKey();

        if ($fresh) {
            Cache::forget($key);
        }

        try {
            $keywords = Cache::remember($key, now()->addMinutes(self::READ_TTL_MINUTES), fn () => $this->client->keywords());
        } catch (RuntimeException $exception) {
            return ['state' => 'unavailable', 'message' => $exception->getMessage(), 'action_url' => $this->client->baseUrl() ?: null];
        }

        $rows = collect($keywords)
            ->filter(fn ($row) => is_array($row) && filled($row['keyword'] ?? null))
            ->groupBy(fn (array $row) => mb_strtolower(trim((string) $row['keyword'])).'|'.strtoupper((string) ($row['country'] ?? '')))
            ->map(function ($group) {
                $first = $group->first();
                $devices = [];

                foreach (self::DEVICES as $device) {
                    $match = $group->first(fn (array $row) => ($row['device'] ?? 'desktop') === $device);
                    $devices[$device] = $match !== null ? $this->device($match) : null;
                }

                $positions = array_filter(array_map(fn (?array $device) => $device['position'] ?? null, $devices));

                return [
                    'keyword' => trim((string) $first['keyword']),
                    'country' => strtoupper((string) ($first['country'] ?? '')),
                    ...$devices,
                    'best' => $positions !== [] ? min($positions) : null,
                    'serp_url' => 'https://www.google.com/search?'.http_build_query(['q' => trim((string) $first['keyword']), 'gl' => strtolower((string) ($first['country'] ?? 'br')), 'hl' => 'pt-BR']),
                ];
            })
            ->sortBy(fn (array $row) => [$row['best'] === null ? 1 : 0, $row['best'] ?? 0, $row['keyword']])
            ->values();

        $updated = collect($keywords)->pluck('lastUpdated')->filter()->max();
        $usedDevices = array_values(array_filter(self::DEVICES, fn (string $device) => $rows->contains(fn (array $row) => $row[$device] !== null)));

        return [
            'state' => 'ready',
            'link' => $this->client->baseUrl() !== '' ? $this->client->baseUrl().'/domain/'.str_replace(['-', '.'], ['_', '-'], $this->client->domain()) : null,
            'domain' => $this->client->domain(),
            'country' => strtoupper((string) config('services.serpbear.country', 'BR')),
            'devices' => $usedDevices !== [] ? $usedDevices : $this->configuredDevices(),
            'can_manage' => $this->client->canManage(),
            'updating' => collect($keywords)->contains(fn ($row) => ! empty($row['updating'])),
            'last_checked_at' => $updated,
            'rows' => $rows->all(),
        ];
    }

    public function refresh(): void
    {
        $this->client->refresh();
        Cache::forget($this->cacheKey());
    }

    /**
     * @param  list<string>  $keywords
     */
    public function add(array $keywords): void
    {
        $country = strtoupper((string) config('services.serpbear.country', 'BR'));
        $rows = [];

        foreach ($keywords as $keyword) {
            foreach ($this->configuredDevices() as $device) {
                $rows[] = ['keyword' => $keyword, 'device' => $device, 'country' => $country];
            }
        }

        $this->client->addKeywords($rows);
        Cache::forget($this->cacheKey());
    }

    /**
     * @param  list<int>  $ids
     */
    public function remove(array $ids): void
    {
        $this->client->removeKeywords($ids);
        Cache::forget($this->cacheKey());
    }

    /**
     * @return list<string>
     */
    public function configuredDevices(): array
    {
        return match ((string) config('services.serpbear.devices', 'desktop')) {
            'both' => self::DEVICES,
            'mobile' => ['mobile'],
            default => ['desktop'],
        };
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array{id: int, position: int|null, previous: int|null, best: int|null, url: string|null, updating: bool, error: string|null, history: list<array{date: string, position: int|null}>}
     */
    private function device(array $row): array
    {
        $history = collect(is_array($row['history'] ?? null) ? $row['history'] : [])
            ->map(fn ($position, $date) => ['date' => (string) $date, 'time' => strtotime((string) $date) ?: 0, 'position' => (int) $position > 0 ? (int) $position : null])
            ->sortBy('time')
            ->values();

        $position = (int) ($row['position'] ?? 0) > 0 ? (int) $row['position'] : null;
        $previous = $history->count() > 1 ? $history[$history->count() - 2]['position'] : null;
        $best = $history->pluck('position')->filter()->min();
        $error = $row['lastUpdateError'] ?? null;

        return [
            'id' => (int) ($row['ID'] ?? 0),
            'position' => $position,
            'previous' => $previous,
            'best' => $best !== null ? (int) $best : $position,
            'url' => filled($row['url'] ?? null) ? (string) $row['url'] : null,
            'updating' => ! empty($row['updating']),
            'error' => is_array($error) && filled($error['error'] ?? null) ? mb_substr((string) $error['error'], 0, 200) : null,
            'history' => $history->map(fn (array $item) => ['date' => $item['date'], 'position' => $item['position']])->all(),
        ];
    }

    private function cacheKey(): string
    {
        return 'serpbear:rank:'.sha1($this->client->baseUrl().'|'.$this->client->domain());
    }
}

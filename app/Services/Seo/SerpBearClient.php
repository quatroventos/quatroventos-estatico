<?php

namespace App\Services\Seo;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Cliente da API do SerpBear (rank tracker self-hosted).
 * A chave de API só libera leitura e atualização de posições; adicionar/remover palavras e
 * consultar o top 10 exigem a sessão do painel, aberta com usuário e senha (cookie "token").
 */
class SerpBearClient
{
    private const TOKEN_TTL_HOURS = 12;

    public function configured(): bool
    {
        return filled(config('services.serpbear.url')) && filled(config('services.serpbear.key')) && filled(config('services.serpbear.domain'));
    }

    public function canManage(): bool
    {
        return filled(config('services.serpbear.url')) && filled(config('services.serpbear.username')) && filled(config('services.serpbear.password'));
    }

    public function baseUrl(): string
    {
        return rtrim((string) config('services.serpbear.url'), '/');
    }

    public function domain(): string
    {
        return (string) preg_replace('#^https?://#', '', rtrim(trim((string) config('services.serpbear.domain')), '/'));
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function domains(): array
    {
        return array_values((array) ($this->api('GET', '/api/domains')['domains'] ?? []));
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function keywords(): array
    {
        return array_values((array) ($this->api('GET', '/api/keywords', ['domain' => $this->domain()])['keywords'] ?? []));
    }

    /**
     * Coloca as palavras na fila do scraper. Sem IDs atualiza todas as do domínio.
     *
     * @param  list<int>  $ids
     */
    public function refresh(array $ids = []): void
    {
        $this->api('POST', '/api/refresh', $ids === [] ? ['id' => 'all', 'domain' => $this->domain()] : ['id' => implode(',', $ids)]);
    }

    /**
     * @param  list<array{keyword: string, device: string, country: string}>  $keywords
     */
    public function addKeywords(array $keywords): void
    {
        $this->session(fn (PendingRequest $request) => $request->post($this->baseUrl().'/api/keywords', [
            'keywords' => array_map(fn (array $row) => [...$row, 'domain' => $this->domain(), 'tags' => '', 'city' => ''], $keywords),
        ]));
    }

    /**
     * @param  list<int>  $ids
     */
    public function removeKeywords(array $ids): void
    {
        $this->session(fn (PendingRequest $request) => $request->delete($this->baseUrl().'/api/keywords?'.http_build_query(['id' => implode(',', $ids)])));
    }

    /**
     * Raspa o Google agora para um termo, sem guardar no SerpBear. Gasta uma consulta do scraper.
     *
     * @return list<array{position: int, title: string, url: string}>
     */
    public function searchResults(string $keyword, string $country, string $device = 'desktop'): array
    {
        $data = $this->session(fn (PendingRequest $request) => $request->get($this->baseUrl().'/api/refresh', ['keyword' => $keyword, 'country' => $country, 'device' => $device]));

        return collect((array) data_get($data, 'searchResult.results', []))
            ->filter(fn ($row) => is_array($row) && filled($row['url'] ?? null))
            ->map(fn (array $row, int $index) => [
                'position' => (int) ($row['position'] ?? $index + 1),
                'title' => trim((string) ($row['title'] ?? '')),
                'url' => trim((string) $row['url']),
            ])
            ->values()
            ->all();
    }

    /**
     * Confere se o login do painel funciona.
     */
    public function checkLogin(): void
    {
        Cache::forget($this->tokenKey());
        $this->token();
    }

    /**
     * Chamada autenticada com a chave de API (só as rotas que o SerpBear libera para ela).
     *
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    private function api(string $method, string $path, array $query = []): array
    {
        if (! filled(config('services.serpbear.url')) || ! filled(config('services.serpbear.key'))) {
            throw new RuntimeException('SerpBear não configurado. Preencha a URL e a chave de API em SEO > Integrações.');
        }

        $url = $this->baseUrl().$path.($query !== [] ? '?'.http_build_query($query) : '');

        return $this->decode($this->send(fn () => Http::withToken((string) config('services.serpbear.key'))
            ->acceptJson()
            ->timeout((int) config('services.serpbear.timeout', 60))
            ->send($method, $url)), apiKey: true);
    }

    /**
     * Chamada com a sessão do painel. Token vencido é renovado uma vez.
     *
     * @param  callable(PendingRequest): Response  $callback
     * @return array<string, mixed>
     */
    private function session(callable $callback): array
    {
        if (! $this->canManage()) {
            throw new RuntimeException('Para adicionar ou remover palavras, preencha usuário e senha do SerpBear em SEO > Integrações.');
        }

        $request = fn () => $callback(Http::withHeaders(['Cookie' => 'token='.$this->token()])->acceptJson()->timeout((int) config('services.serpbear.timeout', 60)));
        $response = $this->send($request);

        if ($response->status() === 401) {
            Cache::forget($this->tokenKey());
            $response = $this->send($request);
        }

        return $this->decode($response, apiKey: false);
    }

    private function token(): string
    {
        $cached = Cache::get($this->tokenKey());

        if (is_string($cached) && $cached !== '') {
            return $cached;
        }

        $response = $this->send(fn () => Http::acceptJson()
            ->timeout((int) config('services.serpbear.timeout', 60))
            ->post($this->baseUrl().'/api/login', [
                'username' => (string) config('services.serpbear.username'),
                'password' => (string) config('services.serpbear.password'),
            ]));

        if ($response->failed()) {
            throw new RuntimeException('O SerpBear recusou o login. Confira usuário e senha em SEO > Integrações.');
        }

        $token = $response->cookies()->getCookieByName('token')?->getValue();

        if (! filled($token) && preg_match('/(?:^|,\s*)token=([^;,\s]+)/', implode(',', $response->headers()['Set-Cookie'] ?? []), $match) === 1) {
            $token = $match[1];
        }

        if (! filled($token)) {
            throw new RuntimeException('O SerpBear aceitou o login mas não devolveu a sessão. Confira se a variável SECRET está definida nele.');
        }

        Cache::put($this->tokenKey(), $token, now()->addHours(self::TOKEN_TTL_HOURS));

        return $token;
    }

    /**
     * @param  callable(): Response  $request
     */
    private function send(callable $request): Response
    {
        try {
            return $request();
        } catch (ConnectionException) {
            throw new RuntimeException('Não foi possível conectar ao SerpBear. Verifique a URL e se o serviço está no ar.');
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function decode(Response $response, bool $apiKey): array
    {
        $data = $response->json();
        $error = is_array($data) && filled($data['error'] ?? null) ? (string) $data['error'] : null;

        if ($response->status() === 401) {
            throw new RuntimeException($apiKey ? 'O SerpBear recusou a chave de API. Confira a variável APIKEY do SerpBear.' : 'O SerpBear recusou a sessão. Confira usuário e senha.');
        }

        if ($response->failed() || $error !== null) {
            throw new RuntimeException($this->translate($error ?? 'HTTP '.$response->status()));
        }

        return is_array($data) ? $data : [];
    }

    private function translate(string $error): string
    {
        return match (true) {
            str_contains($error, 'Scraper has not been set up') => 'O scraper do SerpBear não está configurado. Escolha um em Settings > Scraper no SerpBear.',
            str_contains($error, 'Domain is Required') => 'Defina o domínio do SerpBear em SEO > Integrações.',
            str_contains($error, 'Error Scraping') => 'O scraper do SerpBear não conseguiu consultar o Google agora. Tente de novo em alguns minutos.',
            default => 'SerpBear: '.mb_substr($error, 0, 200),
        };
    }

    private function tokenKey(): string
    {
        return 'serpbear:token:'.sha1($this->baseUrl().'|'.config('services.serpbear.username').'|'.config('services.serpbear.password'));
    }
}

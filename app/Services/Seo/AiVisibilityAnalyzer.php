<?php

namespace App\Services\Seo;

use App\Models\AiVisibilityReport;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Nota pública de prontidão para IA: quinze critérios alinhados aos essentials
 * de domínio elegíveis do is-agentic / Ora (sites de marketing), em escala 0–100.
 *
 * @phpstan-type Check array{id: string, label: string, status: 'good'|'ok'|'bad', points: int, max: int, message: string}
 * @phpstan-type Fix array{title: string, impact: string, minutes: int, body: string, code?: string|null}
 * @phpstan-type Report array{url: string, host: string, score: int, band: string, niche: ?string, checks: list<Check>, fixes: list<Fix>, response_ms: int}
 */
class AiVisibilityAnalyzer
{
    public const AI_BOTS = ['GPTBot', 'ClaudeBot', 'PerplexityBot', 'Google-Extended', 'Applebot-Extended', 'OAI-SearchBot'];

    public const AGENT_FETCH_UA = 'ChatGPT-User/1.0 (+https://openai.com/bot)';

    public const COMPLETE_TYPES = [
        'LocalBusiness', 'ProfessionalService', 'Service', 'Product', 'SoftwareApplication',
        'FAQPage', 'Organization', 'Corporation', 'Store', 'Restaurant',
    ];

    public const HALF_TYPES = ['WebSite', 'WebPage', 'BreadcrumbList', 'ImageObject', 'Person'];

    public const ORG_TYPES = [
        'Organization', 'Corporation', 'LocalBusiness', 'ProfessionalService',
        'Store', 'Restaurant', 'MedicalBusiness', 'LegalService',
    ];

    /**
     * @return Report
     */
    public function analyze(string $rawUrl): array
    {
        $url = $this->normalizeUrl($rawUrl);
        $home = $this->fetch($url);
        $html = $home['body'];
        $finalUrl = $home['url'];
        $host = (string) parse_url($finalUrl, PHP_URL_HOST);
        $origin = $this->origin($finalUrl);

        $robots = $this->fetchText($origin.'/robots.txt');
        $llms = $this->fetchText($origin.'/llms.txt');
        if ($llms === null || trim($llms) === '') {
            $llms = $this->fetchText($origin.'/.well-known/llms.txt');
        }
        if ($llms === null || trim($llms) === '') {
            $llms = $this->fetchText($origin.'/llm.txt');
        }
        $sitemap = $this->hasSitemap($origin, $robots);
        $markdown = $this->probeMarkdown($finalUrl);
        $notFound = $this->probeNotFound($origin);
        $bot = $this->probeAgentFetch($finalUrl);

        $checks = [
            $this->robotsCheck($robots),
            $this->crawlerCheck($bot, $home['https'], $home['ms']),
            $this->readableCheck($html),
            $this->markdownCheck($markdown),
            $this->notFoundCheck($notFound),
            $this->redirectCheck($html),
            $this->authCheck($html),
            $this->answersCheck($html),
            $this->schemaCheck($html),
            $this->metadataCheck($html, $origin),
            $this->orgSchemaCheck($html),
            $this->llmsCheck($llms),
            $this->trustCheck($html),
            $this->sitemapCheck($sitemap, $html, $home['headers'] ?? []),
            $this->structureCheck($html),
        ];

        $score = array_sum(array_column($checks, 'points'));
        $fixes = $this->fixes($checks, $origin);

        return [
            'url' => $finalUrl,
            'host' => $host,
            'score' => $score,
            'band' => AiVisibilityReport::bandFor($score),
            'niche' => $this->niche($html),
            'checks' => $checks,
            'fixes' => $fixes,
            'response_ms' => $home['ms'],
        ];
    }

    public function normalizeUrl(string $raw): string
    {
        $raw = trim($raw);
        if ($raw === '') {
            throw new RuntimeException('Informe a URL do site.');
        }

        if (! preg_match('#^https?://#i', $raw)) {
            $raw = 'https://'.$raw;
        }

        $parts = parse_url($raw);
        if ($parts === false || empty($parts['host'])) {
            throw new RuntimeException('URL inválida.');
        }

        $host = strtolower((string) $parts['host']);
        if ($host === 'localhost' || str_ends_with($host, '.localhost') || str_ends_with($host, '.local')) {
            throw new RuntimeException('Use um domínio público.');
        }

        if (filter_var($host, FILTER_VALIDATE_IP)) {
            if (! filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                throw new RuntimeException('Endereço de rede privada não é permitido.');
            }
        }

        $scheme = strtolower((string) ($parts['scheme'] ?? 'https'));
        if (! in_array($scheme, ['http', 'https'], true)) {
            throw new RuntimeException('Só http e https.');
        }

        $path = (string) ($parts['path'] ?? '/');
        $url = $scheme.'://'.$host.($parts['port'] ?? null ? ':'.$parts['port'] : '').($path === '' ? '/' : $path);
        if (! empty($parts['query'])) {
            $url .= '?'.$parts['query'];
        }

        return $url;
    }

    /**
     * @return array{url: string, body: string, ms: int, https: bool, headers: array<string, list<string>>}
     */
    private function fetch(string $url): array
    {
        $this->assertPublicHost($url);

        $started = hrtime(true);

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'QuatroVentos-AiVisibility/1.0 (+https://quatroventos.com.br/'.AiVisibilityReport::PATH.')',
                'Accept' => 'text/html,application/xhtml+xml',
            ])
                ->withOptions(['allow_redirects' => ['max' => 5, 'track_redirects' => true]])
                ->connectTimeout(4)
                ->timeout(12)
                ->retry([200, 400], 0, fn ($exception) => $exception instanceof ConnectionException)
                ->get($url);
        } catch (ConnectionException|RequestException $exception) {
            throw new RuntimeException('Não conseguimos abrir o site. Confira a URL e tente de novo.', 0, $exception);
        }

        $ms = (int) round((hrtime(true) - $started) / 1e6);
        $final = (string) ($response->effectiveUri() ?? $url);
        $this->assertPublicHost($final);

        if ($response->failed()) {
            throw new RuntimeException('O site respondeu HTTP '.$response->status().'.');
        }

        $body = $response->body();
        if (strlen($body) > 2_000_000) {
            $body = substr($body, 0, 2_000_000);
        }

        return [
            'url' => $final,
            'body' => $body,
            'ms' => $ms,
            'https' => str_starts_with(strtolower($final), 'https://'),
            'headers' => $response->headers(),
        ];
    }

    private function fetchText(string $url): ?string
    {
        try {
            $this->assertPublicHost($url);
            $response = Http::withHeaders([
                'User-Agent' => 'QuatroVentos-AiVisibility/1.0',
                'Accept' => 'text/plain,*/*',
            ])->connectTimeout(3)->timeout(6)->get($url);

            if ($response->failed()) {
                return null;
            }

            return substr($response->body(), 0, 200_000);
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * @return array{ok: bool, content_type: string, vary: string, chars: int}
     */
    private function probeMarkdown(string $url): array
    {
        $empty = ['ok' => false, 'content_type' => '', 'vary' => '', 'chars' => 0];

        try {
            $this->assertPublicHost($url);
            $response = Http::withHeaders([
                'User-Agent' => 'QuatroVentos-AiVisibility/1.0',
                'Accept' => 'text/markdown',
            ])
                ->withOptions(['allow_redirects' => ['max' => 5]])
                ->connectTimeout(3)
                ->timeout(8)
                ->get($url);

            if ($response->failed()) {
                return $empty;
            }

            $contentType = strtolower((string) $response->header('Content-Type'));
            $vary = (string) $response->header('Vary');
            $body = trim($response->body());
            $isMarkdown = str_contains($contentType, 'text/markdown')
                || str_contains($contentType, 'text/x-markdown');
            $looksLikeHtml = preg_match('#^\s*<(!doctype|html|head|body)\b#i', $body) === 1;

            return [
                'ok' => $isMarkdown && ! $looksLikeHtml && mb_strlen($body) >= 40,
                'content_type' => $contentType,
                'vary' => $vary,
                'chars' => mb_strlen($body),
            ];
        } catch (\Throwable) {
            return $empty;
        }
    }

    /**
     * @return array{status: int, soft: bool, markdown: bool, useful: bool}
     */
    private function probeNotFound(string $origin): array
    {
        $probe = $origin.'/__qv-404-probe-'.bin2hex(random_bytes(4));
        $fallback = ['status' => 0, 'soft' => true, 'markdown' => false, 'useful' => false];

        try {
            $this->assertPublicHost($probe);
            $response = Http::withHeaders([
                'User-Agent' => 'QuatroVentos-AiVisibility/1.0',
                'Accept' => 'text/markdown, text/html;q=0.9',
            ])
                ->withOptions(['allow_redirects' => false])
                ->connectTimeout(3)
                ->timeout(8)
                ->get($probe);

            $status = $response->status();
            $body = trim($response->body());
            $contentType = strtolower((string) $response->header('Content-Type'));
            $isMarkdown = str_contains($contentType, 'markdown')
                && mb_strlen($body) >= 20
                && preg_match('#^\s*<(!doctype|html)\b#i', $body) !== 1;
            $useful = mb_strlen(strip_tags($body)) >= 20
                && preg_match('#(sitemap|llms\.txt|/docs|não encontr|not found|404|voltar|home)#iu', $body) === 1;

            return [
                'status' => $status,
                'soft' => ! in_array($status, [404, 410], true),
                'markdown' => $isMarkdown,
                'useful' => $useful || $isMarkdown,
            ];
        } catch (\Throwable) {
            return $fallback;
        }
    }

    /**
     * @return array{ok: bool, status: int, challenge: bool, chars: int}
     */
    private function probeAgentFetch(string $url): array
    {
        $empty = ['ok' => false, 'status' => 0, 'challenge' => false, 'chars' => 0];

        try {
            $this->assertPublicHost($url);
            $response = Http::withHeaders([
                'User-Agent' => self::AGENT_FETCH_UA,
                'Accept' => 'text/html,application/xhtml+xml',
            ])
                ->withOptions(['allow_redirects' => ['max' => 5]])
                ->connectTimeout(3)
                ->timeout(8)
                ->get($url);

            $status = $response->status();
            $body = $response->body();
            $challenge = $this->looksLikeBotChallenge($body, $status);

            return [
                'ok' => $response->successful() && ! $challenge && mb_strlen(strip_tags($body)) >= 100,
                'status' => $status,
                'challenge' => $challenge,
                'chars' => mb_strlen(strip_tags($body)),
            ];
        } catch (\Throwable) {
            return $empty;
        }
    }

    private function looksLikeBotChallenge(string $body, int $status): bool
    {
        if (in_array($status, [401, 403, 429, 503], true)) {
            return true;
        }

        return preg_match('/(cf-challenge|attention required|checking your browser|enable javascript and cookies|captcha|access denied|bot detection)/i', $body) === 1
            && mb_strlen($this->mainText($body)) < 400;
    }

    private function assertPublicHost(string $url): void
    {
        $host = strtolower((string) parse_url($url, PHP_URL_HOST));
        if ($host === '' || $host === 'localhost' || str_ends_with($host, '.local')) {
            throw new RuntimeException('Host inválido.');
        }

        $ips = gethostbynamel($host) ?: [];
        foreach ($ips as $ip) {
            if (! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                throw new RuntimeException('O domínio aponta para uma rede privada.');
            }
        }
    }

    private function origin(string $url): string
    {
        $parts = parse_url($url);

        return strtolower((string) ($parts['scheme'] ?? 'https')).'://'.strtolower((string) ($parts['host'] ?? '')).(($parts['port'] ?? null) ? ':'.$parts['port'] : '');
    }

    private function hasSitemap(string $origin, ?string $robots): bool
    {
        if (is_string($robots) && preg_match('/^\s*Sitemap:\s*(\S+)/mi', $robots) === 1) {
            return true;
        }

        foreach (['/sitemap.xml', '/sitemap_index.xml'] as $path) {
            $body = $this->fetchText($origin.$path);
            if (is_string($body) && (str_contains($body, '<urlset') || str_contains($body, '<sitemapindex'))) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Check
     */
    private function robotsCheck(?string $robots): array
    {
        $max = 7;
        if ($robots === null || trim($robots) === '') {
            return $this->check('robots', 'A IA pode entrar no site', 'ok', (int) ($max / 2), $max, 'Não achamos o arquivo de regras (robots.txt). Sem ele, muita IA entra mesmo assim — o ideal é deixar claro que ChatGPT e outras podem ler.');
        }

        $blocked = array_values(array_filter(self::AI_BOTS, fn (string $bot) => AiReadiness::blocksRoot($robots, $bot)));
        $count = count($blocked);

        return match (true) {
            $count === 0 => $this->check('robots', 'A IA pode entrar no site', 'good', $max, $max, 'As regras do site não mandam ChatGPT, Claude, Perplexity e Google (IA) ficarem de fora.'),
            $count <= 3 => $this->check('robots', 'A IA pode entrar no site', 'ok', (int) ($max / 2), $max, 'As regras bloqueiam '.implode(', ', $blocked).'. Até 3 bloqueios = metade dos pontos.'),
            default => $this->check('robots', 'A IA pode entrar no site', 'bad', 0, $max, 'As regras bloqueiam '.implode(', ', $blocked).'. Essas IAs não leem o site.'),
        };
    }

    /**
     * @param  array{ok: bool, status: int, challenge: bool, chars: int}  $bot
     * @return Check
     */
    private function crawlerCheck(array $bot, bool $https, int $ms): array
    {
        $max = 7;
        if (! $https) {
            return $this->check('crawler', 'Agente consegue buscar a home', 'bad', 0, $max, 'Sem https a IA trata o site com menos confiança e muitos crawlers recuam.');
        }

        return match (true) {
            $bot['ok'] && $ms <= 5000 => $this->check('crawler', 'Agente consegue buscar a home', 'good', $max, $max, 'Com User-Agent de agente (ChatGPT-User) a home responde e não há desafio de bot.'),
            $bot['ok'] => $this->check('crawler', 'Agente consegue buscar a home', 'ok', (int) ($max / 2), $max, 'O agente alcança a home, mas a resposta demorou '.number_format($ms / 1000, 1, ',', '').' s.'),
            $bot['challenge'] => $this->check('crawler', 'Agente consegue buscar a home', 'bad', 0, $max, 'WAF/bot challenge bloqueou o User-Agent de agente (HTTP '.$bot['status'].').'),
            default => $this->check('crawler', 'Agente consegue buscar a home', 'bad', 0, $max, $bot['status'] === 0
                ? 'Não foi possível repetir a busca com User-Agent de agente.'
                : 'User-Agent de agente recebeu HTTP '.$bot['status'].'.'),
        };
    }

    /**
     * @return Check
     */
    private function readableCheck(string $html): array
    {
        $max = 10;
        $text = $this->mainText($html);
        $chars = mb_strlen($text);
        $hasH1 = $this->h1($html) !== '';

        return match (true) {
            $hasH1 && $chars >= 500 => $this->check('readable', 'Texto sem depender de JavaScript', 'good', $max, $max, $chars.' caracteres e H1 já vêm no HTML cru — a IA lê sem executar script.'),
            $hasH1 && $chars >= 200 => $this->check('readable', 'Texto sem depender de JavaScript', 'ok', (int) ($max / 2), $max, $chars.' caracteres com H1. Meta: pelo menos 500 caracteres no HTML sem JavaScript.'),
            ! $hasH1 && $chars >= 500 => $this->check('readable', 'Texto sem depender de JavaScript', 'ok', (int) ($max / 2), $max, $chars.' caracteres no HTML, mas falta um H1 claro.'),
            default => $this->check('readable', 'Texto sem depender de JavaScript', 'bad', 0, $max, $hasH1 ? $chars.' caracteres — pouco para a IA montar uma resposta.' : 'Falta H1 e quase não há texto útil no HTML.'),
        };
    }

    /**
     * @param  array{ok: bool, content_type: string, vary: string, chars: int}  $probe
     * @return Check
     */
    private function markdownCheck(array $probe): array
    {
        $max = 8;
        $varyOk = preg_match('/\bAccept\b/i', $probe['vary']) === 1;

        return match (true) {
            $probe['ok'] && $varyOk => $this->check('markdown', 'Versão em Markdown da página', 'good', $max, $max, 'Com Accept: text/markdown a home responde em Markdown e declara Vary: Accept.'),
            $probe['ok'] => $this->check('markdown', 'Versão em Markdown da página', 'ok', (int) ($max / 2), $max, 'Há resposta Markdown, mas falta o cabeçalho Vary: Accept.'),
            $varyOk => $this->check('markdown', 'Versão em Markdown da página', 'ok', (int) round($max / 4), $max, 'Há Vary: Accept, mas a home ainda devolve HTML quando a IA pede Markdown.'),
            default => $this->check('markdown', 'Versão em Markdown da página', 'bad', 0, $max, 'A home não negocia Markdown (Accept: text/markdown). Agentes preferem texto limpo sem HTML.'),
        };
    }

    /**
     * @param  array{status: int, soft: bool, markdown: bool, useful: bool}  $probe
     * @return Check
     */
    private function notFoundCheck(array $probe): array
    {
        $max = 6;

        return match (true) {
            ! $probe['soft'] && $probe['markdown'] => $this->check('not_found', 'Página 404 útil para a IA', 'good', $max, $max, 'URL inexistente devolve HTTP '.$probe['status'].' com corpo em Markdown.'),
            ! $probe['soft'] && $probe['useful'] => $this->check('not_found', 'Página 404 útil para a IA', 'ok', (int) ($max / 2), $max, 'HTTP '.$probe['status'].' correto e texto útil; falta corpo Markdown quando a IA pede Accept: text/markdown.'),
            ! $probe['soft'] => $this->check('not_found', 'Página 404 útil para a IA', 'ok', (int) round($max / 4), $max, 'HTTP '.$probe['status'].' correto, mas a página de erro quase não orienta a IA (sitemap, llms.txt ou docs).'),
            default => $this->check('not_found', 'Página 404 útil para a IA', 'bad', 0, $max, $probe['status'] === 0
                ? 'Não foi possível testar uma URL inexistente.'
                : 'URL inexistente respondeu HTTP '.$probe['status'].' (soft 404). A IA acha que a página existe.'),
        };
    }

    /**
     * @return Check
     */
    private function answersCheck(string $html): array
    {
        $max = 10;
        $text = mb_strtolower($this->mainText($html).' '.$this->title($html).' '.$this->description($html));
        $what = $this->h1($html) !== '' || preg_match('/\b(somos|fazemos|oferecemos|criamos|desenvolvemos|software|site|serviço|serviços|agência|empresa)\b/u', $text) === 1;
        $whom = preg_match('/\b(para (empresas|negócios|times|equipes|você|seu|sua|salões|clínicas|lojas|autônomos)|feito para|voltado (a|para)|quem (precisa|busca))\b/u', $text) === 1;
        $price = preg_match(SeoAnalyzer::PRICE_PATTERN, $text) === 1 || preg_match(SeoAnalyzer::BUDGET_PATTERN, $text) === 1;
        $hits = (int) $what + (int) $whom + (int) $price;

        return match ($hits) {
            3 => $this->check('answers', 'Fica claro o que você faz, para quem e o preço', 'good', $max, $max, 'A página inicial diz o que você oferece, para quem é e quanto custa.'),
            2 => $this->check('answers', 'Fica claro o que você faz, para quem e o preço', 'ok', (int) round($max / 2), $max, 'Duas das três respostas aparecem. Falta '.(! $what ? 'o que é' : (! $whom ? 'para quem é' : 'quanto custa')).'.'),
            default => $this->check('answers', 'Fica claro o que você faz, para quem e o preço', 'bad', 0, $max, 'A página inicial não deixa claro o que é, para quem é e quanto custa.'),
        };
    }

    /**
     * @return Check
     */
    private function schemaCheck(string $html): array
    {
        $max = 7;
        $types = $this->jsonLdTypes($html);
        $complete = array_values(array_intersect($types, self::COMPLETE_TYPES));
        $half = array_values(array_intersect($types, self::HALF_TYPES));

        return match (true) {
            $complete !== [] => $this->check('schema', 'Dados do negócio em formato de máquina', 'good', $max, $max, 'Há um bloco de dados do negócio (JSON-LD) completo: '.implode(', ', array_unique($complete)).'.'),
            $half !== [] => $this->check('schema', 'Dados do negócio em formato de máquina', 'ok', (int) ($max / 2), $max, 'Só '.implode(', ', array_unique($half)).'. Peça para incluir tipo de empresa, serviço, produto ou FAQ.'),
            default => $this->check('schema', 'Dados do negócio em formato de máquina', 'bad', 0, $max, 'Não há bloco de dados do negócio (JSON-LD) na página.'),
        };
    }

    /**
     * @return Check
     */
    private function redirectCheck(string $html): array
    {
        $max = 5;
        $jsRedirect = preg_match('#<meta[^>]+http-equiv=["\']refresh["\']#i', $html) === 1
            || preg_match('#(?:window\.)?location\.(?:href|replace)\s*=#i', $html) === 1;
        $thin = mb_strlen($this->mainText($html)) < 200;

        return match (true) {
            $jsRedirect && $thin => $this->check('redirect', 'Redirecionamento limpo (sem JS)', 'bad', 0, $max, 'A home redireciona com meta-refresh ou JavaScript e quase não tem texto — agentes sem JS ficam presos.'),
            $jsRedirect => $this->check('redirect', 'Redirecionamento limpo (sem JS)', 'ok', (int) ($max / 2), $max, 'Há meta-refresh ou location em JS. Prefira HTTP 301/302 real.'),
            default => $this->check('redirect', 'Redirecionamento limpo (sem JS)', 'good', $max, $max, 'Sem stub de redirect só em JavaScript ou meta-refresh.'),
        };
    }

    /**
     * @return Check
     */
    private function authCheck(string $html): array
    {
        $max = 5;
        $text = $this->mainText($html);
        $chars = mb_strlen($text);
        $hasPassword = preg_match('#<input[^>]+type=["\']password["\']#i', $html) === 1;
        $loginCopy = preg_match('/\b(sign in|log in|entrar|fazer login|autentique-se|área restrita)\b/iu', $text) === 1;

        return match (true) {
            $hasPassword && $chars < 500 => $this->check('auth', 'Conteúdo sem muro de login', 'bad', 0, $max, 'A home parece um muro de login — a IA não passa da autenticação.'),
            $hasPassword || ($loginCopy && $chars < 800) => $this->check('auth', 'Conteúdo sem muro de login', 'ok', (int) ($max / 2), $max, 'Há sinais de login na home. Mantenha a oferta pública fora da área autenticada.'),
            default => $this->check('auth', 'Conteúdo sem muro de login', 'good', $max, $max, 'A home está pública, sem muro de login para o conteúdo principal.'),
        };
    }

    /**
     * @return Check
     */
    private function metadataCheck(string $html, string $origin): array
    {
        $max = 6;
        $titleOk = mb_strlen($this->title($html)) >= 15;
        $descOk = mb_strlen($this->description($html)) >= 50;
        $lang = preg_match('#<html[^>]+lang=["\'][^"\']+["\']#i', $html) === 1;
        $canonical = $this->hasCanonical($html, $origin);
        $ogImage = preg_match('#property=["\']og:image["\']#i', $html) === 1;
        $ogType = preg_match('#property=["\']og:type["\']#i', $html) === 1;
        $signals = (int) $lang + (int) $canonical + (int) $ogImage + (int) $ogType;
        $basics = (int) $titleOk + (int) $descOk;

        return match (true) {
            $signals === 4 && $basics === 2 => $this->check('meta', 'Metadados completos da página', 'good', $max, $max, 'Título, resumo, idioma, canonical, og:image e og:type presentes.'),
            $signals >= 2 || ($basics === 2 && $signals >= 1) => $this->check('meta', 'Metadados completos da página', 'ok', (int) ($max / 2), $max, $signals.'/4 sinais de identificação (idioma, canonical, og:image, og:type)'.($basics < 2 ? '; título/resumo incompletos' : '').'.'),
            $basics > 0 => $this->check('meta', 'Metadados completos da página', 'ok', (int) round($max / 4), $max, 'Só título/resumo básicos. Faltam idioma, canonical e Open Graph.'),
            default => $this->check('meta', 'Metadados completos da página', 'bad', 0, $max, 'Faltam título, resumo e sinais de identificação (idioma, canonical, Open Graph).'),
        };
    }

    /**
     * @return Check
     */
    private function orgSchemaCheck(string $html): array
    {
        $max = 5;
        $nodes = $this->jsonLdNodes($html);
        $org = null;
        foreach ($nodes as $node) {
            $types = array_map('strval', (array) ($node['@type'] ?? []));
            if (array_intersect($types, self::ORG_TYPES) !== []) {
                $org = $node;
                break;
            }
        }

        if ($org === null) {
            return $this->check('org', 'Empresa com contato e endereço no schema', 'bad', 0, $max, 'Sem Organization/LocalBusiness no JSON-LD com dados de contato.');
        }

        $hasContact = isset($org['contactPoint']) || isset($org['email']) || isset($org['telephone']);
        $hasAddress = isset($org['address']);

        return match (true) {
            $hasContact && $hasAddress => $this->check('org', 'Empresa com contato e endereço no schema', 'good', $max, $max, 'Organization com contactPoint/telefone/e-mail e address.'),
            $hasContact || $hasAddress => $this->check('org', 'Empresa com contato e endereço no schema', 'ok', (int) ($max / 2), $max, $hasContact ? 'Há contato no schema; falta address (PostalAddress).' : 'Há address no schema; falta contactPoint, e-mail ou telefone.'),
            default => $this->check('org', 'Empresa com contato e endereço no schema', 'ok', (int) round($max / 4), $max, 'Há tipo de organização no JSON-LD, mas sem contactPoint nem address.'),
        };
    }

    /**
     * @return Check
     */
    private function llmsCheck(?string $llms): array
    {
        $max = 6;
        $body = is_string($llms) ? trim($llms) : '';
        $exists = $body !== '' && mb_strlen($body) >= 40 && ! preg_match('#^\s*<(!doctype|html)\b#i', $body);
        $when = $exists && preg_match('/(when\s+to\s+use|quando\s+usar|use\s+this\s+when|ideal\s+para|use\s+cases|quando\s+escolher|melhor\s+para)/iu', $body) === 1;

        return match (true) {
            $exists && $when => $this->check('llms', 'Instrução para IA (llms.txt)', 'good', $max, $max, 'llms.txt existe e explica quando usar o negócio.'),
            $exists => $this->check('llms', 'Instrução para IA (llms.txt)', 'ok', (int) ($max / 2), $max, 'llms.txt existe, mas falta uma seção “quando usar” com casos de uso claros.'),
            default => $this->check('llms', 'Instrução para IA (llms.txt)', 'bad', 0, $max, 'Sem llms.txt (ou conteúdo vazio). É o guia curto do site para a IA, com “quando usar”.'),
        };
    }

    /**
     * @return Check
     */
    private function trustCheck(string $html): array
    {
        $max = 6;
        $about = preg_match('#href=["\'][^"\']*(/sobre|/about|/quem-somos|/acerca)[^"\']*["\']#iu', $html) === 1;
        $contact = preg_match('#href=["\'][^"\']*(/contato|/contact|/fale-conosco|/contact-us)[^"\']*["\']#iu', $html) === 1;
        $privacy = preg_match('#href=["\'][^"\']*(/privacidade|/privacy|/politica-de-privacidade|/privacy-policy)[^"\']*["\']#iu', $html) === 1;
        $hits = (int) $about + (int) $contact + (int) $privacy;

        return match ($hits) {
            3 => $this->check('trust', 'Sobre, contato e privacidade', 'good', $max, $max, 'Há links para Sobre, Contato e Privacidade na home.'),
            2 => $this->check('trust', 'Sobre, contato e privacidade', 'ok', (int) ($max / 2), $max, 'Duas âncoras de confiança. Falta '.(! $about ? 'Sobre' : (! $contact ? 'Contato' : 'Privacidade')).'.'),
            1 => $this->check('trust', 'Sobre, contato e privacidade', 'ok', (int) round($max / 4), $max, 'Só uma página de confiança ligada na home. Agentes buscam Sobre, Contato e Privacidade.'),
            default => $this->check('trust', 'Sobre, contato e privacidade', 'bad', 0, $max, 'Sem links para Sobre, Contato ou Privacidade na home.'),
        };
    }

    /**
     * @param  array<string, list<string>>  $headers
     * @return Check
     */
    private function sitemapCheck(bool $found, string $html, array $headers): array
    {
        $max = 5;
        $noindex = $this->hasNoindex($html, $headers);

        if ($noindex) {
            return $this->check('sitemap', 'Lista das páginas e home indexável', 'bad', 0, $max, 'A home está com noindex — some dos resultados que a IA usa para achar o site.');
        }

        return $found
            ? $this->check('sitemap', 'Lista das páginas e home indexável', 'good', $max, $max, 'Existe sitemap e a home não está com noindex.')
            : $this->check('sitemap', 'Lista das páginas e home indexável', 'bad', 0, $max, 'Não encontramos a lista de páginas (sitemap.xml).');
    }

    /**
     * @return Check
     */
    private function structureCheck(string $html): array
    {
        $max = 7;
        $text = $this->mainText($html);
        $chars = mb_strlen($text);
        $prefix = $this->charsBeforeMain($html);
        $budgetOk = $chars <= 100_000;
        $startOk = $prefix <= 5_000;

        return match (true) {
            $budgetOk && $startOk => $this->check('structure', 'Conteúdo no início e no tamanho certo', 'good', $max, $max, 'Texto útil começa cedo ('.$prefix.' chars antes do conteúdo) e cabe no orçamento ('.$chars.' chars).'),
            $budgetOk || $startOk => $this->check('structure', 'Conteúdo no início e no tamanho certo', 'ok', (int) ($max / 2), $max, ! $startOk
                ? 'Há '.$prefix.' caracteres antes do conteúdo principal (meta: até 5.000).'
                : 'A home extrai '.$chars.' caracteres — acima do orçamento ~100.000 que a IA costuma truncar.'),
            default => $this->check('structure', 'Conteúdo no início e no tamanho certo', 'bad', 0, $max, 'Conteúdo longe do topo ('.$prefix.' chars) e página grande demais ('.$chars.' chars).'),
        };
    }

    private function charsBeforeMain(string $html): int
    {
        if (preg_match('#(.*)<main\b#is', $html, $match) === 1) {
            return mb_strlen(trim(html_entity_decode(strip_tags($match[1]), ENT_QUOTES | ENT_HTML5)));
        }
        if (preg_match('#(.*)<h1\b#is', $html, $match) === 1) {
            return mb_strlen(trim(html_entity_decode(strip_tags($match[1]), ENT_QUOTES | ENT_HTML5)));
        }

        return 0;
    }

    /**
     * @param  list<Check>  $checks
     * @return list<Fix>
     */
    private function fixes(array $checks, string $origin): array
    {
        $byId = collect($checks)->keyBy('id');
        $fixes = [];

        if (($byId['answers']['status'] ?? 'good') !== 'good') {
            $fixes[] = [
                'title' => 'Na página inicial: diga o que faz, para quem e o preço',
                'impact' => 'alto',
                'minutes' => 60,
                'body' => 'Escreva em texto simples as três respostas. Se não puder mostrar valores, explique como o orçamento funciona (o que muda o preço e o prazo da proposta).',
                'code' => null,
            ];
        }

        if (($byId['readable']['status'] ?? 'good') !== 'good') {
            $fixes[] = [
                'title' => 'Sirva o texto no HTML (com H1), sem depender de JavaScript',
                'impact' => 'alto',
                'minutes' => 90,
                'body' => 'A home precisa chegar com um H1 e pelo menos 500 caracteres de texto útil no HTML cru. Peça ao responsável pelo site para servir o conteúdo no servidor.',
                'code' => null,
            ];
        }

        if (($byId['robots']['status'] ?? 'good') !== 'good') {
            $fixes[] = [
                'title' => 'Deixe a IA entrar (arquivo robots.txt)',
                'impact' => 'alto',
                'minutes' => 20,
                'body' => 'Peça para liberar ChatGPT e outras IAs no robots.txt. Não deixe essas ferramentas bloqueadas.',
                'code' => null,
            ];
        }

        if (($byId['markdown']['status'] ?? 'good') !== 'good') {
            $fixes[] = [
                'title' => 'Negocie Markdown na home (Accept: text/markdown)',
                'impact' => 'alto',
                'minutes' => 120,
                'body' => 'Quando a IA pedir Accept: text/markdown, a home deve responder text/markdown com Vary: Accept (e continuar servindo HTML para navegadores). Veja acceptmarkdown.com.',
                'code' => null,
            ];
        }

        if (($byId['not_found']['status'] ?? 'good') !== 'good') {
            $fixes[] = [
                'title' => 'Corrija a página 404 para agentes',
                'impact' => 'medio',
                'minutes' => 60,
                'body' => 'URL inexistente deve devolver HTTP 404 (não a home). Ideal: corpo Markdown com explicação e link para sitemap, llms.txt ou docs.',
                'code' => null,
            ];
        }

        if (($byId['schema']['status'] ?? 'good') !== 'good') {
            $fixes[] = [
                'title' => 'Peça para colocar os dados do negócio no site (JSON-LD)',
                'impact' => 'medio',
                'minutes' => 60,
                'body' => 'Quem cuida do site cola no cabeçalho da página inicial um bloco descrevendo o negócio. Ajuste nome, descrição e preço.',
                'code' => '<script type="application/ld+json">'."\n".json_encode([
                    '@context' => 'https://schema.org',
                    '@type' => 'ProfessionalService',
                    'name' => 'Nome do seu negócio',
                    'description' => 'Em uma frase: o que você faz e para quem.',
                    'url' => $origin.'/',
                    'areaServed' => 'Brasil',
                    'offers' => ['@type' => 'Offer', 'price' => '49.00', 'priceCurrency' => 'BRL'],
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)."\n</script>",
            ];
        }

        if (($byId['org']['status'] ?? 'good') !== 'good') {
            $fixes[] = [
                'title' => 'Complete Organization no JSON-LD (contato + endereço)',
                'impact' => 'medio',
                'minutes' => 45,
                'body' => 'Inclua contactPoint (e-mail/telefone) e address (PostalAddress) no schema da empresa.',
                'code' => null,
            ];
        }

        if (($byId['llms']['status'] ?? 'good') !== 'good') {
            $fixes[] = [
                'title' => 'Publique llms.txt com “quando usar”',
                'impact' => 'medio',
                'minutes' => 30,
                'body' => 'Crie /llms.txt com o que o negócio faz, links principais e uma seção clara de quando a IA deve indicar você.',
                'code' => "# Nome do negócio\n\nResumo em uma frase.\n\n## Quando usar\n\n- Ideal para …\n- Use quando o cliente precisa de …\n\n## Links\n\n- [Home]({$origin}/)\n- [Contato]({$origin}/contato)\n",
            ];
        }

        if (($byId['trust']['status'] ?? 'good') !== 'good') {
            $fixes[] = [
                'title' => 'Publique Sobre, Contato e Privacidade',
                'impact' => 'baixo',
                'minutes' => 60,
                'body' => 'Três páginas com conteúdo real, ligadas na home. Agentes usam isso para validar se o negócio é legítimo.',
                'code' => null,
            ];
        }

        if (($byId['sitemap']['status'] ?? 'good') !== 'good') {
            $fixes[] = [
                'title' => 'Publique sitemap e tire noindex da home',
                'impact' => 'baixo',
                'minutes' => 20,
                'body' => 'Liste as páginas em /sitemap.xml, aponte no robots.txt e confirme que a home não tem noindex.',
                'code' => null,
            ];
        }

        if (($byId['meta']['status'] ?? 'good') !== 'good') {
            $fixes[] = [
                'title' => 'Complete metadados: idioma, canonical e Open Graph',
                'impact' => 'medio',
                'minutes' => 30,
                'body' => 'Além de título e resumo: html lang, link rel=canonical no próprio domínio, og:image e og:type.',
                'code' => null,
            ];
        }

        if (($byId['crawler']['status'] ?? 'good') !== 'good') {
            $fixes[] = [
                'title' => 'Libere agentes no WAF e use https',
                'impact' => 'alto',
                'minutes' => 60,
                'body' => 'Permita ChatGPT-User / OAI-SearchBot no Cloudflare (ou equivalente), sem desafio de bot na home pública. Mantenha https.',
                'code' => null,
            ];
        }

        if (($byId['redirect']['status'] ?? 'good') !== 'good') {
            $fixes[] = [
                'title' => 'Troque redirect em JS por HTTP 301/302',
                'impact' => 'medio',
                'minutes' => 45,
                'body' => 'Agentes sem JavaScript não seguem location.href nem meta-refresh. Use redirecionamento HTTP real.',
                'code' => null,
            ];
        }

        if (($byId['auth']['status'] ?? 'good') !== 'good') {
            $fixes[] = [
                'title' => 'Deixe a oferta pública fora do login',
                'impact' => 'alto',
                'minutes' => 90,
                'body' => 'A home e as páginas de oferta precisam ser legíveis sem autenticação. Login só para área logada.',
                'code' => null,
            ];
        }

        if (($byId['structure']['status'] ?? 'good') !== 'good') {
            $fixes[] = [
                'title' => 'Aproxime o conteúdo do topo e enxugue a página',
                'impact' => 'baixo',
                'minutes' => 60,
                'body' => 'Marque o conteúdo com <main>, corte menu/cookie antes do texto útil (meta: 5.000 chars) e evite páginas enormes (~100.000 chars).',
                'code' => null,
            ];
        }

        return $fixes;
    }

    private function niche(string $html): ?string
    {
        $blob = trim($this->description($html).' '.$this->title($html).' '.$this->h1($html));
        $blob = Str::of($blob)->replaceMatches('/\s+/u', ' ')->trim()->toString();
        if ($blob === '') {
            return null;
        }

        return Str::limit($blob, 140, '');
    }

    /**
     * @return list<string>
     */
    private function jsonLdTypes(string $html): array
    {
        $types = [];
        foreach ($this->jsonLdNodes($html) as $node) {
            foreach ((array) ($node['@type'] ?? []) as $item) {
                if (is_string($item) && $item !== '') {
                    $types[] = $item;
                }
            }
        }

        return array_values(array_unique($types));
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function jsonLdNodes(string $html): array
    {
        $nodes = [];
        preg_match_all('#<script[^>]+type=["\']application/ld\+json["\'][^>]*>(.*?)</script>#is', $html, $matches);
        foreach ($matches[1] as $json) {
            $data = json_decode(html_entity_decode($json, ENT_QUOTES | ENT_HTML5), true);
            if (! is_array($data)) {
                continue;
            }
            $chunk = isset($data['@graph']) && is_array($data['@graph']) ? $data['@graph'] : [$data];
            foreach ($chunk as $node) {
                if (is_array($node)) {
                    $nodes[] = $node;
                }
            }
        }

        return $nodes;
    }

    private function mainText(string $html): string
    {
        if (preg_match('#<main\b[^>]*>(.*?)</main>#is', $html, $match) === 1) {
            $html = $match[1];
        }
        $html = preg_replace('#<(script|style|noscript|svg)\b[^>]*>.*?</\1>#is', ' ', $html) ?? $html;

        return trim(html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5));
    }

    private function title(string $html): string
    {
        return preg_match('#<title[^>]*>(.*?)</title>#is', $html, $match) === 1
            ? trim(html_entity_decode(strip_tags($match[1]), ENT_QUOTES | ENT_HTML5))
            : '';
    }

    private function h1(string $html): string
    {
        return preg_match('#<h1\b[^>]*>(.*?)</h1>#is', $html, $match) === 1
            ? trim(html_entity_decode(strip_tags($match[1]), ENT_QUOTES | ENT_HTML5))
            : '';
    }

    private function description(string $html): string
    {
        return preg_match('#<meta\s+[^>]*name=["\']description["\'][^>]*content=["\']([^"\']*)["\']#i', $html, $match) === 1
            || preg_match('#<meta\s+[^>]*content=["\']([^"\']*)["\'][^>]*name=["\']description["\']#i', $html, $match) === 1
            ? trim(html_entity_decode($match[1], ENT_QUOTES | ENT_HTML5))
            : '';
    }

    private function hasCanonical(string $html, string $origin): bool
    {
        if (preg_match('#<link[^>]+rel=["\']canonical["\'][^>]+href=["\']([^"\']+)["\']#i', $html, $match) !== 1
            && preg_match('#<link[^>]+href=["\']([^"\']+)["\'][^>]+rel=["\']canonical["\']#i', $html, $match) !== 1) {
            return false;
        }

        $href = trim($match[1]);
        $host = strtolower((string) parse_url($href, PHP_URL_HOST));
        $originHost = strtolower((string) parse_url($origin, PHP_URL_HOST));

        return $host !== '' && $host === $originHost;
    }

    /**
     * @param  array<string, list<string>>  $headers
     */
    private function hasNoindex(string $html, array $headers): bool
    {
        if (preg_match('#<meta[^>]+name=["\'](?:robots|googlebot|bingbot)["\'][^>]+content=["\'][^"\']*noindex#i', $html) === 1
            || preg_match('#<meta[^>]+content=["\'][^"\']*noindex[^"\']*["\'][^>]+name=["\'](?:robots|googlebot|bingbot)["\']#i', $html) === 1) {
            return true;
        }

        foreach ($headers as $name => $values) {
            if (strcasecmp((string) $name, 'X-Robots-Tag') !== 0) {
                continue;
            }
            foreach ($values as $value) {
                if (stripos($value, 'noindex') !== false) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param  'good'|'ok'|'bad'  $status
     * @return Check
     */
    private function check(string $id, string $label, string $status, int $points, int $max, string $message): array
    {
        return compact('id', 'label', 'status', 'points', 'max', 'message');
    }
}

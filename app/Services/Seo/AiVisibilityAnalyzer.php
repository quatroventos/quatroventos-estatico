<?php

namespace App\Services\Seo;

use App\Models\AiVisibilityReport;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Nota pública de prontidão para IA (escala alinhada ao Jataí): nove critérios sobre a home.
 *
 * @phpstan-type Check array{id: string, label: string, status: 'good'|'ok'|'bad', points: int, max: int, message: string}
 * @phpstan-type Fix array{title: string, impact: string, minutes: int, body: string, code?: string|null}
 * @phpstan-type Report array{url: string, host: string, score: int, band: string, niche: ?string, checks: list<Check>, fixes: list<Fix>, response_ms: int}
 */
class AiVisibilityAnalyzer
{
    public const AI_BOTS = ['GPTBot', 'ClaudeBot', 'PerplexityBot', 'Google-Extended', 'Applebot-Extended', 'OAI-SearchBot'];

    public const COMPLETE_TYPES = [
        'LocalBusiness', 'ProfessionalService', 'Service', 'Product', 'SoftwareApplication',
        'FAQPage', 'Organization', 'Corporation', 'Store', 'Restaurant',
    ];

    public const HALF_TYPES = ['WebSite', 'WebPage', 'BreadcrumbList', 'ImageObject', 'Person'];

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
            $llms = $this->fetchText($origin.'/llm.txt');
        }
        $sitemap = $this->hasSitemap($origin, $robots);

        $checks = [
            $this->robotsCheck($robots),
            $this->readableCheck($html),
            $this->answersCheck($html),
            $this->schemaCheck($html),
            $this->speedCheck($finalUrl, $home['ms'], $home['https']),
            $this->titleDescriptionCheck($html),
            $this->llmsCheck($llms),
            $this->trustCheck($html, $origin),
            $this->sitemapCheck($sitemap),
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
     * @return array{url: string, body: string, ms: int, https: bool}
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
        $max = 20;
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
     * @return Check
     */
    private function readableCheck(string $html): array
    {
        $max = 20;
        $text = $this->mainText($html);
        $words = str_word_count($text, 0, 'àáâãäåæçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ');
        $hasTitle = $this->h1($html) !== '' || $this->title($html) !== '';

        return match (true) {
            $hasTitle && $words >= 200 => $this->check('readable', 'Texto da página inicial aparece sem truque', 'good', $max, $max, $words.' palavras já vêm escritas na página, com título.'),
            $hasTitle && $words >= 30 => $this->check('readable', 'Texto da página inicial aparece sem truque', 'ok', (int) ($max / 2), $max, $words.' palavras na página. Acima de 200 a IA tem mais trecho para citar.'),
            default => $this->check('readable', 'Texto da página inicial aparece sem truque', 'bad', 0, $max, $hasTitle ? $words.' palavras na página — pouco para a IA montar uma resposta.' : 'Falta título e quase não há texto na página.'),
        };
    }

    /**
     * @return Check
     */
    private function answersCheck(string $html): array
    {
        $max = 15;
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
        $max = 10;
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
    private function speedCheck(string $url, int $ms, bool $https): array
    {
        $max = 10;
        if (! $https) {
            return $this->check('speed', 'Site com cadeado e resposta rápida', 'bad', 0, $max, 'Sem cadeado (https). Isso reduz confiança.');
        }

        return match (true) {
            $ms <= 2000 => $this->check('speed', 'Site com cadeado e resposta rápida', 'good', $max, $max, 'Com cadeado e resposta em '.number_format($ms / 1000, 1, ',', '').' s.'),
            $ms <= 5000 => $this->check('speed', 'Site com cadeado e resposta rápida', 'ok', (int) ($max / 2), $max, 'Cadeado ok, mas a página inicial levou '.number_format($ms / 1000, 1, ',', '').' s.'),
            default => $this->check('speed', 'Site com cadeado e resposta rápida', 'bad', 0, $max, 'Cadeado ok, porém a página inicial demorou '.number_format($ms / 1000, 1, ',', '').' s.'),
        };
    }

    /**
     * @return Check
     */
    private function titleDescriptionCheck(string $html): array
    {
        $max = 10;
        $title = $this->title($html);
        $description = $this->description($html);
        $titleOk = mb_strlen($title) >= 15;
        $descOk = mb_strlen($description) >= 50;

        return match (true) {
            $titleOk && $descOk => $this->check('meta', 'Título e resumo da página', 'good', $max, $max, 'Título e resumo com tamanho útil.'),
            $titleOk || $descOk => $this->check('meta', 'Título e resumo da página', 'ok', (int) ($max / 2), $max, $titleOk ? 'Título ok; resumo curto ou ausente.' : 'Resumo ok; título curto ou ausente.'),
            default => $this->check('meta', 'Título e resumo da página', 'bad', 0, $max, 'Faltam título e resumo da página.'),
        };
    }

    /**
     * @return Check
     */
    private function llmsCheck(?string $llms): array
    {
        $max = 5;
        $ok = is_string($llms) && trim($llms) !== '' && mb_strlen(trim($llms)) >= 40;

        return $ok
            ? $this->check('llms', 'Resumo para IA (llms.txt)', 'good', $max, $max, 'Existe um resumo llms.txt contando o que o negócio faz.')
            : $this->check('llms', 'Resumo para IA (llms.txt)', 'bad', 0, $max, 'Sem llms.txt. É um arquivo curto que explica o negócio para a IA.');
    }

    /**
     * @return Check
     */
    private function trustCheck(string $html, string $origin): array
    {
        $max = 5;
        $about = preg_match('#href=["\'][^"\']*(/sobre|/about|/quem-somos|/acerca)[^"\']*["\']#iu', $html) === 1;
        $author = preg_match('#name=["\']author["\']|property=["\']article:author["\']|rel=["\']author["\']#i', $html) === 1;
        $date = preg_match('#property=["\']article:modified_time["\']|property=["\']og:updated_time["\']|datetime=["\']\d{4}#i', $html) === 1
            || preg_match('#atualizad[oa]\s+em|<time[\s>]#iu', $html) === 1;
        $trust = $author || $date;

        return match (true) {
            $about && $trust => $this->check('trust', 'Quem está por trás e quando atualizou', 'good', $max, $max, 'Há página Sobre e sinal de autor ou data de atualização.'),
            $about || $trust => $this->check('trust', 'Quem está por trás e quando atualizou', 'ok', (int) ($max / 2), $max, $about ? 'Página Sobre ok; falta autor ou data visível.' : 'Há autor/data; falta página Sobre ligada na home.'),
            default => $this->check('trust', 'Quem está por trás e quando atualizou', 'bad', 0, $max, 'Sem página Sobre nem autor/data.'),
        };
    }

    /**
     * @return Check
     */
    private function sitemapCheck(bool $found): array
    {
        $max = 5;

        return $found
            ? $this->check('sitemap', 'Lista das páginas (sitemap)', 'good', $max, $max, 'Existe uma lista das páginas do site (sitemap.xml).')
            : $this->check('sitemap', 'Lista das páginas (sitemap)', 'bad', 0, $max, 'Não encontramos a lista de páginas (sitemap.xml).');
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

        if (($byId['robots']['status'] ?? 'good') !== 'good') {
            $fixes[] = [
                'title' => 'Deixe a IA entrar (arquivo robots.txt)',
                'impact' => 'alto',
                'minutes' => 20,
                'body' => 'Peça para liberar ChatGPT e outras IAs no robots.txt. Não deixe essas ferramentas bloqueadas.',
                'code' => null,
            ];
        }

        if (($byId['readable']['status'] ?? 'good') !== 'good') {
            $fixes[] = [
                'title' => 'Deixe o texto da página inicial aparecer sem depender de efeito',
                'impact' => 'alto',
                'minutes' => 90,
                'body' => 'A página inicial precisa chegar com título e pelo menos 200 palavras já escritas. Peça ao responsável pelo site para servir o texto no servidor, não só depois que a tela “liga”.',
                'code' => null,
            ];
        }

        if (($byId['llms']['status'] ?? 'good') !== 'good') {
            $fixes[] = [
                'title' => 'Publique um resumo curto para IA (llms.txt)',
                'impact' => 'baixo',
                'minutes' => 30,
                'body' => 'Crie o arquivo /llms.txt com o nome do negócio, um resumo e links das páginas principais.',
                'code' => null,
            ];
        }

        if (($byId['trust']['status'] ?? 'good') !== 'good') {
            $fixes[] = [
                'title' => 'Mostre quem está por trás e quando atualizou',
                'impact' => 'baixo',
                'minutes' => 45,
                'body' => 'Crie uma página Sobre no menu e indique a data de atualização dos textos principais.',
                'code' => null,
            ];
        }

        if (($byId['sitemap']['status'] ?? 'good') !== 'good') {
            $fixes[] = [
                'title' => 'Publique a lista das páginas (sitemap.xml)',
                'impact' => 'baixo',
                'minutes' => 20,
                'body' => 'Liste as páginas do site em /sitemap.xml e aponte esse arquivo nas regras do site (robots.txt).',
                'code' => null,
            ];
        }

        if (($byId['meta']['status'] ?? 'good') !== 'good') {
            $fixes[] = [
                'title' => 'Ajuste o título e o resumo da página',
                'impact' => 'medio',
                'minutes' => 20,
                'body' => 'O título da aba deve explicar o negócio; o resumo (meta description) precisa ter pelo menos 50 caracteres.',
                'code' => null,
            ];
        }

        if (($byId['speed']['status'] ?? 'good') !== 'good') {
            $fixes[] = [
                'title' => 'Acelere a página inicial e use cadeado (https)',
                'impact' => 'medio',
                'minutes' => 120,
                'body' => 'Meta: resposta em até 2 segundos com https. Cache, imagens leves e redirecionamento de http para https.',
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
        preg_match_all('#<script[^>]+type=["\']application/ld\+json["\'][^>]*>(.*?)</script>#is', $html, $matches);
        foreach ($matches[1] as $json) {
            $data = json_decode(html_entity_decode($json, ENT_QUOTES | ENT_HTML5), true);
            if (! is_array($data)) {
                continue;
            }
            $nodes = isset($data['@graph']) && is_array($data['@graph']) ? $data['@graph'] : [$data];
            foreach ($nodes as $node) {
                if (! is_array($node)) {
                    continue;
                }
                $type = $node['@type'] ?? null;
                foreach ((array) $type as $item) {
                    if (is_string($item) && $item !== '') {
                        $types[] = $item;
                    }
                }
            }
        }

        return array_values(array_unique($types));
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

    /**
     * @param  'good'|'ok'|'bad'  $status
     * @return Check
     */
    private function check(string $id, string $label, string $status, int $points, int $max, string $message): array
    {
        return compact('id', 'label', 'status', 'points', 'max', 'message');
    }
}

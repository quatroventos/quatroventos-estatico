<?php

namespace App\Services\Seo;

use App\Models\Page;
use App\Services\SitemapService;
use App\Services\SystemOptions;

/**
 * Prontidão do site inteiro para buscadores de IA: acesso dos robôs, arquivos de descoberta,
 * dados da empresa e páginas que as IAs usam para confirmar quem está por trás do site.
 *
 * @phpstan-import-type Check from SeoAnalyzer
 */
class AiReadiness
{
    public const AI_BOTS = ['GPTBot', 'OAI-SearchBot', 'ChatGPT-User', 'ClaudeBot', 'PerplexityBot', 'Google-Extended'];

    public function __construct(private SitemapService $sitemap, private SystemOptions $options) {}

    /**
     * @return array{score: int, checks: list<Check>}
     */
    public function site(): array
    {
        $checks = [];

        $blocked = array_values(array_filter(self::AI_BOTS, fn (string $bot) => self::blocksRoot($this->sitemap->robots(), $bot)));
        $checks[] = $blocked === []
            ? $this->check('robots_ai', 'Robôs de IA liberados', 'good', 'robots.txt libera '.implode(', ', self::AI_BOTS).'.', 3)
            : $this->check('robots_ai', 'Robôs de IA liberados', 'bad', 'robots.txt bloqueia '.implode(', ', $blocked).'. Essas IAs não leem o site.', 3);

        $links = preg_match_all('#\]\(https?://#', $this->sitemap->llms());
        $checks[] = $links >= 3
            ? $this->check('llms_txt', 'llms.txt', 'good', $links.' páginas listadas em '.$this->sitemap->llmsUrl().'.', 2)
            : $this->check('llms_txt', 'llms.txt', 'bad', 'llms.txt sem páginas. Publique páginas ou revise o texto na aba Configuração.', 2);

        $entries = count($this->sitemap->entries());
        $checks[] = $entries > 0
            ? $this->check('sitemap', 'Sitemap', 'good', $entries.' URLs no sitemap.', 1)
            : $this->check('sitemap', 'Sitemap', 'bad', 'Sitemap vazio.', 1);

        $https = str_starts_with((string) config('app.url'), 'https://');
        $checks[] = $https
            ? $this->check('https', 'HTTPS', 'good', 'URLs canônicas em HTTPS.', 1)
            : $this->check('https', 'HTTPS', 'bad', 'APP_URL não usa HTTPS. Canonical e JSON-LD saem com http.', 1);

        $site = $this->options->site();
        $missing = array_keys(array_filter([
            'telefone ou e-mail' => blank($site?->phone) && blank($site?->email),
            'endereço' => blank($site?->address),
            'redes sociais' => blank($site?->instagram_url) && blank($site?->linkedin_url) && blank($site?->facebook_url),
            'descrição padrão' => blank($site?->seo_description),
        ]));
        $checks[] = $missing === []
            ? $this->check('organization', 'Dados da empresa', 'good', 'Nome, contato, endereço e redes saem no JSON-LD de todas as páginas.', 3)
            : $this->check('organization', 'Dados da empresa', count($missing) > 2 ? 'bad' : 'ok', 'Faltam em Configurações: '.implode(', ', $missing).'.', 3);

        $published = Page::query()->originals()->published()->get();
        $slugs = $published->pluck('slug')->map(fn ($slug) => (string) $slug);

        $checks[] = $slugs->contains(fn (string $slug) => preg_match('/^(sobre|about|quem-somos)/', $slug) === 1)
            ? $this->check('about_page', 'Página Sobre', 'good', 'Publicada e marcada como AboutPage.', 2)
            : $this->check('about_page', 'Página Sobre', 'bad', 'Sem página Sobre publicada. IAs usam essa página para confirmar quem é a empresa.', 2);

        $checks[] = $slugs->contains(fn (string $slug) => preg_match('/^(contato|contact|fale-conosco)/', $slug) === 1)
            ? $this->check('contact_page', 'Página de contato', 'good', 'Publicada e marcada como ContactPage.', 1)
            : $this->check('contact_page', 'Página de contato', 'ok', 'Sem página de contato publicada.', 1);

        $home = $published->first(fn (Page $page) => $page->isHome());
        $homeText = $home !== null ? PageDocument::make('page', $home->sections(), ['home' => true])->fullText() : '';
        $homeSections = $home?->sections() ?? [];
        $checks[] = match (true) {
            $home === null => $this->check('home_price', 'Preço ou orçamento na home', 'bad', 'Home não publicada.', 2),
            SeoAnalyzer::hasPrices($homeSections) || preg_match(SeoAnalyzer::PRICE_PATTERN, $homeText) === 1 => $this->check('home_price', 'Preço ou orçamento na home', 'good', 'A home mostra preços.', 2),
            preg_match(SeoAnalyzer::BUDGET_PATTERN, $homeText) === 1 => $this->check('home_price', 'Preço ou orçamento na home', 'ok', 'A home fala de orçamento, sem faixa de valor.', 2),
            default => $this->check('home_price', 'Preço ou orçamento na home', 'bad', 'A home não diz como funciona o preço. Uma seção curta explicando o orçamento resolve.', 2),
        };

        $withFaq = $published->filter(fn (Page $page) => collect($page->sections())->contains(fn (array $section) => ($section['type'] ?? null) === 'site.faq'))->count();
        $checks[] = $withFaq >= 3
            ? $this->check('faq_pages', 'Páginas com FAQ', 'good', $withFaq.' páginas com perguntas frequentes (FAQPage).', 1)
            : $this->check('faq_pages', 'Páginas com FAQ', 'ok', $withFaq.' de '.$published->count().' páginas com perguntas frequentes. As de serviço ganham mais com elas.', 1);

        return ['score' => $this->score($checks), 'checks' => $checks];
    }

    /**
     * Considera o grupo do próprio robô; sem grupo dele, vale o de "*".
     */
    public static function blocksRoot(string $robots, string $bot): bool
    {
        $groups = [];
        $agents = [];
        $collecting = false;

        foreach (preg_split('/\R/', $robots) ?: [] as $line) {
            $line = trim((string) preg_replace('/#.*/', '', $line));
            if (! str_contains($line, ':')) {
                continue;
            }

            [$field, $value] = array_map('trim', explode(':', $line, 2));
            $field = strtolower($field);

            if ($field === 'user-agent') {
                $agents = $collecting ? [strtolower($value)] : [...$agents, strtolower($value)];
                $collecting = false;
                foreach ($agents as $agent) {
                    $groups[$agent] ??= [];
                }

                continue;
            }

            if ($field === 'disallow' || $field === 'allow') {
                $collecting = true;
                foreach ($agents as $agent) {
                    $groups[$agent][] = [$field, $value];
                }
            }
        }

        $rules = $groups[strtolower($bot)] ?? $groups['*'] ?? [];

        return in_array(['disallow', '/'], $rules, true) && ! in_array(['allow', '/'], $rules, true);
    }

    /**
     * @param  list<Check>  $checks
     */
    private function score(array $checks): int
    {
        $total = 0;
        $earned = 0.0;

        foreach ($checks as $check) {
            $total += $check['weight'];
            $earned += $check['weight'] * ['good' => 1, 'ok' => 0.5, 'bad' => 0][$check['status']];
        }

        return $total > 0 ? (int) round($earned / $total * 100) : 0;
    }

    /**
     * @param  'good'|'ok'|'bad'  $status
     * @return Check
     */
    private function check(string $id, string $label, string $status, string $message, int $weight): array
    {
        return ['id' => $id, 'label' => $label, 'status' => $status, 'message' => $message, 'weight' => $weight, 'fix' => null];
    }
}

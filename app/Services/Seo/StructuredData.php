<?php

namespace App\Services\Seo;

use App\Models\MenuItem;
use App\Models\Page;
use App\Models\SiteSetting;
use App\Services\SystemOptions;
use App\Services\Translation\LinkLocalizer;
use App\Support\Locales;
use App\Support\Seo;
use Illuminate\Support\Collection;

/**
 * JSON-LD das páginas do CMS, montado só com dados que já existem no painel
 * (Configurações, menu principal e seções da página).
 */
class StructuredData
{
    private const HOME_LABEL = ['pt-BR' => 'Início', 'en' => 'Home', 'es' => 'Inicio'];

    private const ABOUT_SLUGS = '/^(sobre|about|quem-somos|acerca)/';

    private const CONTACT_SLUGS = '/^(contato|contact|contacto|fale-conosco)/';

    public function __construct(private SystemOptions $options, private LinkLocalizer $links) {}

    /**
     * @param  array<string, mixed>  $seo
     * @return array<string, mixed>
     */
    public function page(Page $page, array $seo, string $locale): array
    {
        $site = $this->options->site();
        $home = url(Locales::path($locale, '/'));
        $url = (string) ($seo['canonical'] ?? url($page->publicPath()));
        $sections = $page->sections();

        $webPage = array_filter([
            '@type' => $this->pageType($page),
            '@id' => $url.'#webpage',
            'url' => $url,
            'name' => $seo['title'] ?? null,
            'description' => $seo['description'] ?? null,
            'inLanguage' => Locales::hreflang($locale),
            'isPartOf' => ['@id' => $home.'#website'],
            'about' => ['@id' => $home.'#organization'],
            'primaryImageOfPage' => filled($seo['og_image'] ?? null) ? ['@type' => 'ImageObject', 'url' => $seo['og_image']] : null,
            'datePublished' => $page->published_at?->toIso8601String(),
            'dateModified' => $page->updated_at?->toIso8601String(),
            'breadcrumb' => $page->isHome() ? null : ['@id' => $url.'#breadcrumb'],
        ], fn ($value) => $value !== null);

        $graph = [
            $this->organization($site, $home, $locale),
            [
                '@type' => 'WebSite',
                '@id' => $home.'#website',
                'url' => $home,
                'name' => $this->siteName($site),
                'inLanguage' => Locales::hreflang($locale),
                'publisher' => ['@id' => $home.'#organization'],
            ],
            $webPage,
        ];

        if (! $page->isHome()) {
            $graph[] = [
                '@type' => 'BreadcrumbList',
                '@id' => $url.'#breadcrumb',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => self::HOME_LABEL[$locale] ?? self::HOME_LABEL['pt-BR'], 'item' => $home],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => $this->shortTitle($seo), 'item' => $url],
                ],
            ];
        }

        if (! $page->isHome() && $this->isServicePage($page, $locale)) {
            $graph[] = array_filter([
                '@type' => 'Service',
                '@id' => $url.'#service',
                'name' => $this->shortTitle($seo),
                'serviceType' => $this->shortTitle($seo),
                'description' => $seo['description'] ?? null,
                'url' => $url,
                'provider' => ['@id' => $home.'#organization'],
                'areaServed' => ['@type' => 'Country', 'name' => 'Brasil'],
                'offers' => $this->offers($sections, $url) ?: null,
            ], fn ($value) => $value !== null);
        }

        $faq = $this->faq($sections);
        if ($faq !== []) {
            $graph[] = [
                '@type' => 'FAQPage',
                '@id' => $url.'#faq',
                'mainEntity' => $faq,
            ];
        }

        return ['@context' => 'https://schema.org', '@graph' => $graph];
    }

    /**
     * Um bloco ld+json por entidade: validadores simples leem só o @type do topo e ignoram @graph.
     * As referências por @id continuam valendo entre blocos.
     *
     * @param  array<string, mixed>|null  $jsonld
     * @return list<array<string, mixed>>
     */
    public static function scripts(?array $jsonld): array
    {
        if ($jsonld === null || $jsonld === []) {
            return [];
        }

        if (! isset($jsonld['@graph']) || ! is_array($jsonld['@graph'])) {
            return [$jsonld];
        }

        $context = $jsonld['@context'] ?? 'https://schema.org';

        return array_values(array_map(fn (array $node) => ['@context' => $context, ...$node], $jsonld['@graph']));
    }

    /**
     * @return array<string, mixed>
     */
    public function organization(?SiteSetting $site, string $home, string $locale): array
    {
        $logo = media($site?->logo_path);
        $logo = $logo !== '' ? $logo : asset('assets/logo-quatroventos.svg');
        $defaults = Seo::defaults($site);

        return array_filter([
            '@type' => 'ProfessionalService',
            '@id' => $home.'#organization',
            'name' => $this->siteName($site),
            'url' => $home,
            'logo' => $logo,
            'image' => filled($defaults['seo_og_image'] ?? null) ? media($defaults['seo_og_image']) : $logo,
            'description' => $defaults['seo_description'] ?? null,
            'telephone' => filled($site?->phone) ? (string) $site->phone : null,
            'email' => filled($site?->email) ? (string) $site->email : null,
            'address' => $this->address($site?->translated('address', $locale)),
            'areaServed' => ['@type' => 'Country', 'name' => 'Brasil'],
            'sameAs' => array_values(array_filter([
                $site?->instagram_url,
                $site?->linkedin_url,
                $site?->facebook_url,
            ], fn ($url) => is_string($url) && preg_match('#^https?://#i', $url) === 1)) ?: null,
            'contactPoint' => filled($site?->phone) || filled($site?->email) ? array_filter([
                '@type' => 'ContactPoint',
                'contactType' => 'sales',
                'telephone' => filled($site?->phone) ? (string) $site->phone : null,
                'email' => filled($site?->email) ? (string) $site->email : null,
                'availableLanguage' => array_values(array_map(fn (array $item) => Locales::hreflang($item['code']), Locales::publicList())),
            ]) : null,
        ], fn ($value) => $value !== null && $value !== '');
    }

    /**
     * "Curitiba - PR" vira cidade e UF; qualquer outro formato vai inteiro como endereço.
     *
     * @return array<string, string>|null
     */
    private function address(mixed $value): ?array
    {
        $text = trim(strip_tags((string) $value));
        if ($text === '') {
            return null;
        }

        if (preg_match('/^([^,\-\/\d]+?)\s*[-,\/]\s*([A-Z]{2})$/u', $text, $match) === 1) {
            return ['@type' => 'PostalAddress', 'addressLocality' => trim($match[1]), 'addressRegion' => $match[2], 'addressCountry' => 'BR'];
        }

        return ['@type' => 'PostalAddress', 'streetAddress' => $text, 'addressCountry' => 'BR'];
    }

    private function pageType(Page $page): string
    {
        if ($page->isHome()) {
            return 'WebPage';
        }

        return match (true) {
            preg_match(self::ABOUT_SLUGS, (string) $page->slug) === 1 => 'AboutPage',
            preg_match(self::CONTACT_SLUGS, (string) $page->slug) === 1 => 'ContactPage',
            default => 'WebPage',
        };
    }

    /**
     * Serviço é a página que está no menu principal como subitem ou como item que agrupa subitens.
     */
    public function isServicePage(Page $page, string $locale): bool
    {
        $path = $page->publicPath();

        return $this->servicePaths($this->options->menu('header', $locale), $locale, false)
            ->contains(fn (array $item) => $item['page_id'] === $page->id || $item['path'] === $path);
    }

    /**
     * @param  Collection<int, MenuItem>  $items
     * @return Collection<int, array{page_id: int|null, path: string}>
     */
    private function servicePaths(Collection $items, string $locale, bool $nested): Collection
    {
        return $items->flatMap(function (MenuItem $item) use ($locale, $nested) {
            $children = $item->relationLoaded('children') ? $item->children : collect();
            $own = $nested || $children->isNotEmpty()
                ? [['page_id' => $item->page_id !== null ? (int) $item->page_id : null, 'path' => $this->path((string) $item->href, $locale)]]
                : [];

            return [...$own, ...$this->servicePaths($children, $locale, true)->all()];
        });
    }

    private function path(string $href, string $locale): string
    {
        $localized = $this->links->href($href, $locale);
        $path = (string) (parse_url($localized, PHP_URL_PATH) ?? '');

        return $path === '' ? '/' : (rtrim($path, '/') ?: '/');
    }

    /**
     * @param  list<array<string, mixed>>  $sections
     * @return list<array<string, mixed>>
     */
    private function offers(array $sections, string $url): array
    {
        $offers = [];

        foreach ($sections as $section) {
            $data = is_array($section['data'] ?? null) ? $section['data'] : [];

            if (($section['type'] ?? null) === 'site.plans') {
                foreach (is_array($data['items'] ?? null) ? $data['items'] : [] as $item) {
                    $offers[] = $this->offer($item['name'] ?? '', $item['price'] ?? '', $url);
                }
            }

            if (($section['type'] ?? null) === 'site.pricing') {
                foreach (is_array($data['categories'] ?? null) ? $data['categories'] : [] as $category) {
                    foreach (is_array($category['plans'] ?? null) ? $category['plans'] : [] as $plan) {
                        $offers[] = $this->offer($plan['name'] ?? '', $plan['monthly'] ?? '', $url, 'MON');
                    }
                }
            }
        }

        return array_values(array_filter($offers));
    }

    /**
     * @return array<string, mixed>|null
     */
    private function offer(mixed $name, mixed $price, string $url, ?string $unit = null): ?array
    {
        $amount = self::parsePrice((string) $price);
        $name = trim(strip_tags((string) $name));
        if ($amount === null || $name === '') {
            return null;
        }

        return array_filter([
            '@type' => 'Offer',
            'name' => $name,
            'price' => $amount,
            'priceCurrency' => 'BRL',
            'url' => $url,
            'priceSpecification' => $unit === null ? null : [
                '@type' => 'UnitPriceSpecification',
                'price' => $amount,
                'priceCurrency' => 'BRL',
                'unitCode' => $unit,
            ],
        ], fn ($value) => $value !== null);
    }

    /**
     * Aceita "R$ 1.290", "45,90", "45.90" e "199/mês". Sem número retorna null.
     */
    public static function parsePrice(string $value): ?string
    {
        if (preg_match('/\d[\d.,]*/', $value, $match) !== 1) {
            return null;
        }

        $number = rtrim($match[0], '.,');
        if (str_contains($number, ',')) {
            $number = str_replace(['.', ','], ['', '.'], $number);
        } elseif (preg_match('/\.\d{3}$/', $number) === 1) {
            $number = str_replace('.', '', $number);
        }

        return is_numeric($number) && (float) $number > 0 ? $number : null;
    }

    /**
     * @param  list<array<string, mixed>>  $sections
     * @return list<array<string, mixed>>
     */
    private function faq(array $sections): array
    {
        $questions = [];

        foreach ($sections as $section) {
            if (($section['type'] ?? null) !== 'site.faq') {
                continue;
            }

            $items = is_array($section['data']['items'] ?? null) ? $section['data']['items'] : [];
            foreach ($items as $item) {
                $question = trim(strip_tags((string) ($item['question'] ?? '')));
                $answer = trim((string) preg_replace('/\s+/u', ' ', strip_tags((string) ($item['answer'] ?? ''))));
                if ($question === '' || $answer === '') {
                    continue;
                }

                $questions[] = [
                    '@type' => 'Question',
                    'name' => $question,
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $answer],
                ];
            }
        }

        return $questions;
    }

    /**
     * @param  array<string, mixed>  $seo
     */
    private function shortTitle(array $seo): string
    {
        $title = (string) ($seo['title'] ?? '');

        return trim((string) preg_replace('/\s*\|.*$/', '', $title)) ?: $title;
    }

    private function siteName(?SiteSetting $site): string
    {
        return filled($site?->name) ? (string) $site->name : Seo::FALLBACK_SITE_NAME;
    }
}

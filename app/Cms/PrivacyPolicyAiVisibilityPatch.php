<?php

namespace App\Cms;

use App\Models\Page;

class PrivacyPolicyAiVisibilityPatch
{
    /**
     * @var array<string, array{slug: string, hero_date: string, collect: string, use: string, legal: string, retain: string}>
     */
    public const LOCALES = [
        'pt-BR' => [
            'slug' => 'politica-de-privacidade',
            'hero_date' => '21 de novembro de 2026',
            'collect' => '<p><strong>Quando você usa a ferramenta “Como aparecer no ChatGPT?”:</strong> o endereço (URL) do site informado e o resultado da análise (nota, itens verificados e recomendações). Também pode ser registrado o endereço IP da requisição, para segurança e prevenção de abuso.</p>',
            'use' => '<li>Estudar o uso da ferramenta “Como aparecer no ChatGPT?” e melhorar o próprio teste. A URL e o resultado podem ficar armazenados para essa finalidade de estudo. Esses dados não são usados para fins comerciais.</li>',
            'legal' => '<li><strong>Legítimo interesse</strong>, para a segurança do site, a prevenção de fraudes e o estudo do uso da ferramenta de prontidão para IA, sem uso comercial desses registros.</li>',
            'retain' => ' Os registros da ferramenta “Como aparecer no ChatGPT?” (URL e resultado) são mantidos pelo tempo necessário ao estudo e à melhoria do teste; depois disso, são eliminados ou anonimizados.',
        ],
        'en' => [
            'slug' => 'privacy-policy',
            'hero_date' => 'November 21, 2026',
            'collect' => '<p><strong>When you use the “How to appear in ChatGPT?” tool:</strong> the website URL you submit and the analysis result (score, checks and recommendations). The request IP address may also be stored for security and abuse prevention.</p>',
            'use' => '<li>Study how the “How to appear in ChatGPT?” tool is used and improve the test itself. The URL and result may be stored for that study purpose only. This data is not used for commercial purposes.</li>',
            'legal' => '<li><strong>Legitimate interest</strong>, for site security, fraud prevention, and studying use of the AI readiness tool, without commercial use of those records.</li>',
            'retain' => ' Records from the “How to appear in ChatGPT?” tool (URL and result) are kept only as long as needed for study and test improvement; after that, they are deleted or anonymized.',
        ],
        'es' => [
            'slug' => 'politica-privacidad',
            'hero_date' => '21 de noviembre de 2026',
            'collect' => '<p><strong>Cuando usas la herramienta “¿Cómo aparecer en ChatGPT?”:</strong> la dirección (URL) del sitio indicada y el resultado del análisis (nota, ítems verificados y recomendaciones). También se puede registrar la dirección IP de la solicitud, por seguridad y prevención de abuso.</p>',
            'use' => '<li>Estudiar el uso de la herramienta “¿Cómo aparecer en ChatGPT?” y mejorar la propia prueba. La URL y el resultado pueden almacenarse con esa finalidad de estudio. Estos datos no se usan con fines comerciales.</li>',
            'legal' => '<li><strong>Interés legítimo</strong>, para la seguridad del sitio, la prevención de fraudes y el estudio del uso de la herramienta de preparación para IA, sin uso comercial de esos registros.</li>',
            'retain' => ' Los registros de la herramienta “¿Cómo aparecer en ChatGPT?” (URL y resultado) se conservan el tiempo necesario para el estudio y la mejora de la prueba; después, se eliminan o anonimizan.',
        ],
    ];

    public static function apply(): int
    {
        $updated = 0;

        foreach (self::LOCALES as $locale => $copy) {
            $page = Page::query()->where('slug', $copy['slug'])->where('locale', $locale)->first();
            if (! $page) {
                continue;
            }

            if (self::applyToPage($page, $locale, $copy)) {
                $updated++;
            }
        }

        return $updated;
    }

    /**
     * @param  array{slug: string, hero_date: string, collect: string, use: string, legal: string, retain: string}  $copy
     */
    public static function applyToPage(Page $page, string $locale, array $copy): bool
    {
        $content = PageContentMigrator::normalize($page->content, $page->template);
        $sections = $content['sections'];
        $changed = false;

        foreach ($sections as &$section) {
            $type = (string) ($section['type'] ?? '');
            $data = is_array($section['data'] ?? null) ? $section['data'] : [];
            $title = mb_strtolower(strip_tags((string) ($data['title'] ?? '')));
            $body = (string) ($data['body'] ?? '');
            $subtitle = (string) ($data['subtitle'] ?? '');

            if ($type === 'site.hero' && $subtitle !== '' && ! str_contains($subtitle, $copy['hero_date'])) {
                $data['subtitle'] = preg_replace(
                    '/Última atualização:.*?\.|Last updated:.*?\.|Última actualización:.*?\./u',
                    match ($locale) {
                        'en' => 'Last updated: '.$copy['hero_date'].'.',
                        'es' => 'Última actualización: '.$copy['hero_date'].'.',
                        default => 'Última atualização: '.$copy['hero_date'].'.',
                    },
                    $subtitle
                ) ?: $subtitle;
                $changed = true;
            }

            if (str_contains($title, 'quais dados') || str_contains($title, 'what data') || str_contains($title, 'qué datos') || str_contains($title, 'que datos')) {
                if (! str_contains($body, 'Como aparecer no ChatGPT') && ! str_contains($body, 'How to appear in ChatGPT') && ! str_contains($body, 'Cómo aparecer en ChatGPT')) {
                    $data['body'] = rtrim($body).$copy['collect'];
                    $changed = true;
                }
            }

            if (str_contains($title, 'para que usamos') || str_contains($title, 'how we use') || str_contains($title, 'para qué usamos')) {
                if (! str_contains($body, 'fins comerciais') && ! str_contains($body, 'commercial purposes') && ! str_contains($body, 'fines comerciales')) {
                    $data['body'] = str_contains($body, '</ul>')
                        ? str_replace('</ul>', $copy['use'].'</ul>', $body)
                        : rtrim($body).'<ul>'.$copy['use'].'</ul>';
                    $changed = true;
                }
            }

            if (str_contains($title, 'bases legais') || str_contains($title, 'legal bases') || str_contains($title, 'bases legales')) {
                if (str_contains($body, 'Legítimo interesse') || str_contains($body, 'Legitimate interest') || str_contains($body, 'Interés legítimo')) {
                    $replaced = preg_replace(
                        '/<li><strong>(?:Legítimo interesse|Legitimate interest|Interés legítimo)<\/strong>.*?<\/li>/u',
                        $copy['legal'],
                        $body,
                        1
                    );
                    if (is_string($replaced) && $replaced !== $body) {
                        $data['body'] = $replaced;
                        $changed = true;
                    }
                }
            }

            if (str_contains($title, 'por quanto tempo') || str_contains($title, 'how long') || str_contains($title, 'cuánto tiempo') || str_contains($title, 'cuanto tiempo')) {
                if (! str_contains($body, 'Como aparecer no ChatGPT') && ! str_contains($body, 'How to appear in ChatGPT') && ! str_contains($body, 'Cómo aparecer en ChatGPT')) {
                    $data['body'] = rtrim($body).$copy['retain'];
                    $changed = true;
                }
            }

            $section['data'] = $data;
        }
        unset($section);

        if (! $changed) {
            return false;
        }

        $page->content = PageContentMigrator::normalize(['sections' => $sections], $page->template);
        $page->save();

        return true;
    }
}

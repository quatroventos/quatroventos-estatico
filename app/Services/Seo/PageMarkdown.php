<?php

namespace App\Services\Seo;

/**
 * Markdown limpo a partir do HTML pré-renderizado ou do payload do CMS.
 */
class PageMarkdown
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function fromPayload(array $payload, ?string $html = null): string
    {
        $fromHtml = is_string($html) && trim($html) !== '' ? $this->fromHtml($html) : '';
        if (mb_strlen(trim($fromHtml)) >= 40) {
            return $this->finish($fromHtml, $payload);
        }

        return $this->finish($this->fromSections($payload), $payload);
    }

    public function notFound(string $origin): string
    {
        $origin = rtrim($origin, '/');

        return implode("\n", [
            '# Página não encontrada',
            '',
            'O endereço mudou ou foi removido. Os serviços e cases continuam a partir do início.',
            '',
            '- [Início]('.$origin.'/)',
            '- [Sitemap]('.$origin.'/sitemap.xml)',
            '- [llms.txt]('.$origin.'/llms.txt)',
            '',
        ]);
    }

    public function fromHtml(string $html): string
    {
        $html = preg_replace('#<(script|style|noscript)\b[^>]*>.*?</\1>#is', '', $html) ?? $html;

        libxml_use_internal_errors(true);
        $dom = new \DOMDocument;
        $loaded = $dom->loadHTML('<?xml encoding="UTF-8">'.$html, LIBXML_NOERROR | LIBXML_NONET);
        libxml_clear_errors();

        if (! $loaded) {
            return $this->plainFallback($html);
        }

        $xpath = new \DOMXPath($dom);
        foreach (['//script', '//style', '//noscript', '//header', '//footer', '//nav', '//aside'] as $query) {
            foreach ($xpath->query($query) ?: [] as $node) {
                $node->parentNode?->removeChild($node);
            }
        }

        $root = $xpath->query('//*[@id="conteudo"]')->item(0)
            ?? $xpath->query('//main')->item(0)
            ?? $xpath->query('//article')->item(0)
            ?? $dom->documentElement;

        if (! $root instanceof \DOMNode) {
            return $this->plainFallback($html);
        }

        $lines = [];
        $this->walk($root, $lines);

        return trim(preg_replace("/\n{3,}/", "\n\n", implode("\n", $lines)) ?? '')."\n";
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function fromSections(array $payload): string
    {
        $lines = [];
        $title = $this->plain((string) data_get($payload, 'seo.title', ''));
        $description = $this->plain((string) data_get($payload, 'seo.description', ''));

        if ($title !== '') {
            $lines[] = '# '.$this->stripSiteSuffix($title);
            $lines[] = '';
        }

        if ($description !== '') {
            $lines[] = $description;
            $lines[] = '';
        }

        foreach ((array) ($payload['sections'] ?? []) as $section) {
            if (! is_array($section)) {
                continue;
            }

            $this->sectionLines((array) ($section['data'] ?? []), $lines);
        }

        return trim(implode("\n", $lines))."\n";
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  list<string>  $lines
     */
    private function sectionLines(array $data, array &$lines): void
    {
        foreach (['title', 'heading', 'h1', 'h2'] as $key) {
            $value = $this->plain((string) ($data[$key] ?? ''));
            if ($value !== '') {
                $lines[] = '## '.$value;
                $lines[] = '';
            }
        }

        foreach (['subtitle', 'lead', 'body', 'text', 'intro', 'content'] as $key) {
            $value = $this->rich((string) ($data[$key] ?? ''));
            if ($value !== '') {
                $lines[] = $value;
                $lines[] = '';
            }
        }

        foreach (['items', 'steps', 'cards', 'rows', 'points', 'features'] as $listKey) {
            $items = $data[$listKey] ?? null;
            if (! is_array($items)) {
                continue;
            }

            foreach ($items as $item) {
                if (is_string($item)) {
                    $text = $this->plain($item);
                    if ($text !== '') {
                        $lines[] = '- '.$text;
                    }

                    continue;
                }

                if (! is_array($item)) {
                    continue;
                }

                $label = $this->plain((string) ($item['title'] ?? $item['label'] ?? $item['name'] ?? ''));
                $body = $this->rich((string) ($item['body'] ?? $item['text'] ?? $item['description'] ?? ''));
                if ($label !== '' && $body !== '') {
                    $lines[] = '- **'.$label.'**: '.$body;
                } elseif ($label !== '') {
                    $lines[] = '- '.$label;
                } elseif ($body !== '') {
                    $lines[] = '- '.$body;
                }
            }

            $lines[] = '';
        }
    }

    /**
     * @param  list<string>  $lines
     */
    private function walk(\DOMNode $node, array &$lines): void
    {
        if ($node instanceof \DOMElement) {
            $tag = strtolower($node->tagName);

            if (in_array($tag, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'], true)) {
                $text = $this->plain($node->textContent ?? '');
                if ($text !== '') {
                    $level = (int) $tag[1];
                    $lines[] = str_repeat('#', $level).' '.$text;
                    $lines[] = '';
                }

                return;
            }

            if ($tag === 'p') {
                $text = $this->inline($node);
                if ($text !== '') {
                    $lines[] = $text;
                    $lines[] = '';
                }

                return;
            }

            if ($tag === 'li') {
                $text = $this->inline($node);
                if ($text !== '') {
                    $lines[] = '- '.$text;
                }

                return;
            }

            if (in_array($tag, ['ul', 'ol'], true)) {
                foreach ($node->childNodes as $child) {
                    $this->walk($child, $lines);
                }
                $lines[] = '';

                return;
            }

            if ($tag === 'a') {
                return;
            }
        }

        if ($node instanceof \DOMText) {
            return;
        }

        foreach ($node->childNodes ?? [] as $child) {
            $this->walk($child, $lines);
        }
    }

    private function inline(\DOMElement $node): string
    {
        $parts = [];

        foreach ($node->childNodes as $child) {
            if ($child instanceof \DOMText) {
                $parts[] = $child->wholeText;

                continue;
            }

            if (! $child instanceof \DOMElement) {
                continue;
            }

            $tag = strtolower($child->tagName);
            $text = $this->plain($child->textContent ?? '');

            if ($tag === 'a' && $text !== '') {
                $href = trim((string) $child->getAttribute('href'));
                $parts[] = $href !== '' ? '['.$text.']('.$href.')' : $text;

                continue;
            }

            if (in_array($tag, ['strong', 'b'], true) && $text !== '') {
                $parts[] = '**'.$text.'**';

                continue;
            }

            if (in_array($tag, ['em', 'i'], true) && $text !== '') {
                $parts[] = '*'.$text.'*';

                continue;
            }

            $parts[] = $text;
        }

        return $this->plain(implode('', $parts));
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function finish(string $markdown, array $payload): string
    {
        $markdown = trim($markdown);
        $canonical = $this->plain((string) data_get($payload, 'seo.canonical', ''));

        if ($markdown === '') {
            $title = $this->stripSiteSuffix($this->plain((string) data_get($payload, 'seo.title', 'Quatro Ventos')));
            $description = $this->plain((string) data_get($payload, 'seo.description', ''));
            $markdown = '# '.$title;
            if ($description !== '') {
                $markdown .= "\n\n".$description;
            }
        }

        if ($canonical !== '' && ! str_contains($markdown, $canonical)) {
            $markdown .= "\n\nFonte: ".$canonical;
        }

        return rtrim($markdown)."\n";
    }

    private function rich(string $value): string
    {
        if ($value === '') {
            return '';
        }

        if (str_contains($value, '<')) {
            return $this->plain(html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }

        return $this->plain($value);
    }

    private function plain(string $value): string
    {
        $value = html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = preg_replace('/\s+/u', ' ', $value) ?? $value;

        return trim($value);
    }

    private function stripSiteSuffix(string $title): string
    {
        return trim((string) preg_replace('/\s*\|\s*Quatro Ventos\s*$/iu', '', $title));
    }

    private function plainFallback(string $html): string
    {
        return $this->plain($html)."\n";
    }
}

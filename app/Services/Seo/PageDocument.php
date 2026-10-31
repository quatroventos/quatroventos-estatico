<?php

namespace App\Services\Seo;

use App\Cms\SectionCatalog;

/**
 * Leitura da página como o visitante e o Google veem: H1, subtítulos, introdução, blocos de texto e alts.
 * Cada trecho guarda o alvo de onde veio, para a IA conseguir reescrever exatamente aquele campo.
 *
 * Alvos: ['type' => 'section', 'index' => 2, 'path' => 'items.0.body'] ou ['type' => 'field', 'name' => 'summary'].
 *
 * @phpstan-type Target array{type: string, index?: int, path?: string, name?: string}
 * @phpstan-type Chunk array{text: string, raw: string, rich: bool, label: string, target: Target}
 */
class PageDocument
{
    private const TEXT_TYPES = ['text', 'textarea', 'rich'];

    private const SKIP_NAMES = '/(^|_)(href|url|whatsapp|anchor|id|icon|glyph|log|panel_id|period|cta|label)$/';

    private const ALT_NAMES = '/(^|_)alt$/';

    /** @var Chunk|null */
    public ?array $h1 = null;

    /** @var list<Chunk> */
    public array $headings = [];

    /** @var Chunk|null */
    public ?array $intro = null;

    /** @var list<Chunk> */
    public array $blocks = [];

    /** @var list<Chunk> */
    public array $alts = [];

    /**
     * @param  list<array{type: string, data: array<string, mixed>}>  $sections
     * @param  array{title?: string|null, summary?: string|null, client?: string|null, cover_alt?: string|null, og_image_alt?: string|null}  $fields
     */
    public static function make(string $kind, array $sections, array $fields = []): self
    {
        $document = new self;

        if ($kind === 'case') {
            $document->h1 = self::chunk((string) ($fields['title'] ?? ''), false, 'Título do case', ['type' => 'field', 'name' => 'title']);
            $document->intro = self::chunk((string) ($fields['summary'] ?? ''), false, 'Resumo do case', ['type' => 'field', 'name' => 'summary']);

            if (filled($fields['cover_alt'] ?? null)) {
                $document->alts[] = self::chunk((string) $fields['cover_alt'], false, 'Alt da capa', ['type' => 'field', 'name' => 'cover_alt']);
            }
        } elseif (filled($fields['og_image_alt'] ?? null)) {
            $document->alts[] = self::chunk((string) $fields['og_image_alt'], false, 'Alt da OG image', ['type' => 'field', 'name' => 'og_image_alt']);
        }

        foreach (array_values($sections) as $index => $section) {
            $definition = SectionCatalog::find($section['type']);

            if ($definition === null) {
                continue;
            }

            $data = is_array($section['data'] ?? null) ? $section['data'] : [];
            $label = (string) ($definition['label'] ?? $section['type']);
            $title = trim(strip_tags((string) ($data['title'] ?? '')));

            if ($title !== '') {
                $chunk = self::chunk($title, false, $label.' > Título', ['type' => 'section', 'index' => $index, 'path' => 'title']);

                if ($document->h1 === null && $kind !== 'case') {
                    $document->h1 = $chunk;
                } else {
                    $document->headings[] = $chunk;
                }
            }

            $document->collect((array) ($definition['fields'] ?? []), $data, $index, '', $label.' > ');
        }

        if ($document->intro === null || trim($document->intro['text']) === '') {
            $document->intro = $document->blocks[0] ?? null;
        }

        return $document;
    }

    public function fullText(): string
    {
        $parts = array_filter([
            $this->h1['text'] ?? null,
            $this->intro !== null && ! in_array($this->intro, $this->blocks, true) ? $this->intro['text'] : null,
            ...array_column($this->headings, 'text'),
            ...array_column($this->blocks, 'text'),
        ]);

        return trim(implode("\n", $parts));
    }

    /**
     * Texto com rótulos, para a IA entender a estrutura da página.
     */
    public function outline(int $limit = 12000): string
    {
        $lines = [];

        if ($this->h1 !== null) {
            $lines[] = '[H1] '.$this->h1['text'];
        }

        if ($this->intro !== null && ! in_array($this->intro, $this->blocks, true)) {
            $lines[] = '[Introdução] '.$this->intro['text'];
        }

        foreach ($this->headings as $heading) {
            $lines[] = '[H2] '.$heading['text'];
        }

        foreach ($this->blocks as $block) {
            $lines[] = '['.$block['label'].'] '.$block['text'];
        }

        return mb_substr(implode("\n", $lines), 0, $limit);
    }

    public function wordCount(): int
    {
        return count(Keyword::words($this->fullText()));
    }

    /**
     * Trecho do documento pelo alvo, para conferir o valor atual antes de reescrever.
     *
     * @param  array<string, mixed>  $target
     * @return Chunk|null
     */
    public function find(array $target): ?array
    {
        foreach ([$this->h1, $this->intro, ...$this->headings, ...$this->blocks, ...$this->alts] as $chunk) {
            if ($chunk !== null && $chunk['target'] == $target) {
                return $chunk;
            }
        }

        return null;
    }

    /**
     * @param  list<array<string, mixed>>  $fields
     * @param  array<string, mixed>  $data
     */
    private function collect(array $fields, array $data, int $index, string $prefix, string $labelPrefix): void
    {
        foreach ($fields as $field) {
            $name = (string) ($field['name'] ?? '');
            $type = (string) ($field['type'] ?? '');
            $value = $data[$name] ?? null;
            $label = $labelPrefix.($field['label'] ?? $name);

            if ($name === '' || ($prefix === '' && $name === 'title')) {
                continue;
            }

            if (preg_match(self::ALT_NAMES, $name) === 1) {
                if (is_string($value) && trim($value) !== '') {
                    $this->alts[] = self::chunk($value, false, $label, ['type' => 'section', 'index' => $index, 'path' => $prefix.$name]);
                }

                continue;
            }

            if (preg_match(self::SKIP_NAMES, $name) === 1) {
                continue;
            }

            if ($type === 'repeater' && is_array($value)) {
                foreach (array_values($value) as $i => $item) {
                    if (is_string($item)) {
                        if (trim(strip_tags($item)) !== '') {
                            $this->blocks[] = self::chunk($item, ($field['itemType'] ?? '') === 'rich', $label.' '.($i + 1), ['type' => 'section', 'index' => $index, 'path' => $prefix.$name.'.'.$i]);
                        }

                        continue;
                    }

                    if (is_array($item)) {
                        $this->collect((array) ($field['fields'] ?? []), $item, $index, $prefix.$name.'.'.$i.'.', $label.' '.($i + 1).' > ');
                    }
                }

                continue;
            }

            if (in_array($type, self::TEXT_TYPES, true) && is_string($value) && trim(strip_tags($value)) !== '') {
                $this->blocks[] = self::chunk($value, $type === 'rich', $label, ['type' => 'section', 'index' => $index, 'path' => $prefix.$name]);
            }
        }
    }

    /**
     * @param  array<string, mixed>  $target
     * @return Chunk
     */
    private static function chunk(string $value, bool $rich, string $label, array $target): array
    {
        return [
            'text' => trim((string) preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags(str_replace(['</p>', '<br>', '</li>'], ' ', $value))))),
            'raw' => $value,
            'rich' => $rich,
            'label' => $label,
            'target' => $target,
        ];
    }
}

<?php

namespace App\Cms;

class SiteSectionDefinition
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function all(): array
    {
        return [
            [
                'label' => 'Hero',
                'preview' => 'hero',
                'fields' => [
                    ['type' => 'text', 'name' => 'title', 'label' => 'Título'],
                    ['type' => 'repeater', 'name' => 'rotating', 'label' => 'Linha rotativa (a primeira completa o título)', 'simple' => true, 'itemType' => 'text'],
                    ['type' => 'rich', 'name' => 'subtitle', 'label' => 'Abertura'],
                    ['type' => 'repeater', 'name' => 'badges', 'label' => 'Selos', 'simple' => true, 'itemType' => 'text'],
                    ['type' => 'text', 'name' => 'primary_label', 'label' => 'Botão principal'],
                    ['type' => 'text', 'name' => 'primary_href', 'label' => 'Link do botão principal'],
                    ['type' => 'text', 'name' => 'secondary_label', 'label' => 'Botão secundário'],
                    ['type' => 'text', 'name' => 'secondary_href', 'label' => 'Link do botão secundário'],
                ],
            ],
            [
                'label' => 'Lista',
                'preview' => 'list',
                'fields' => self::itemsFields(true),
            ],
            [
                'label' => 'Texto',
                'preview' => 'prose',
                'fields' => [
                    ['type' => 'text', 'name' => 'title', 'label' => 'Título'],
                    ['type' => 'rich', 'name' => 'body', 'label' => 'Texto'],
                ],
            ],
            [
                'label' => 'Cards',
                'preview' => 'cards',
                'fields' => self::itemsFields(true),
            ],
            [
                'label' => 'Etapas',
                'preview' => 'steps',
                'fields' => self::itemsFields(false),
            ],
            [
                'label' => 'Portfólio',
                'preview' => 'portfolio',
                'fields' => self::itemsFields(false),
            ],
            [
                'label' => 'Logos',
                'preview' => 'logos',
                'fields' => [
                    ['type' => 'text', 'name' => 'title', 'label' => 'Título'],
                    [
                        'type' => 'repeater',
                        'name' => 'items',
                        'label' => 'Logos',
                        'simple' => false,
                        'fields' => [
                            ['type' => 'text', 'name' => 'name', 'label' => 'Nome'],
                            ['type' => 'media', 'name' => 'image', 'label' => 'Logo', 'kind' => 'image'],
                            ['type' => 'text', 'name' => 'image_alt', 'label' => 'Alt'],
                        ],
                    ],
                ],
            ],
            [
                'label' => 'Depoimentos',
                'preview' => 'quotes',
                'fields' => [
                    ['type' => 'text', 'name' => 'title', 'label' => 'Título'],
                    [
                        'type' => 'repeater',
                        'name' => 'items',
                        'label' => 'Depoimentos',
                        'simple' => false,
                        'fields' => [
                            ['type' => 'rich', 'name' => 'quote', 'label' => 'Depoimento'],
                            ['type' => 'text', 'name' => 'author', 'label' => 'Autor'],
                            ['type' => 'text', 'name' => 'company', 'label' => 'Empresa'],
                        ],
                    ],
                ],
            ],
            [
                'label' => 'Perguntas frequentes',
                'preview' => 'faq',
                'fields' => [
                    ['type' => 'text', 'name' => 'title', 'label' => 'Título'],
                    [
                        'type' => 'repeater',
                        'name' => 'items',
                        'label' => 'Perguntas',
                        'simple' => false,
                        'fields' => [
                            ['type' => 'text', 'name' => 'question', 'label' => 'Pergunta'],
                            ['type' => 'rich', 'name' => 'answer', 'label' => 'Resposta'],
                        ],
                    ],
                ],
            ],
            [
                'label' => 'Planos',
                'preview' => 'plans',
                'fields' => [
                    ['type' => 'text', 'name' => 'title', 'label' => 'Título'],
                    ['type' => 'rich', 'name' => 'intro', 'label' => 'Introdução'],
                    [
                        'type' => 'repeater',
                        'name' => 'items',
                        'label' => 'Planos',
                        'simple' => false,
                        'fields' => [
                            ['type' => 'text', 'name' => 'name', 'label' => 'Nome'],
                            ['type' => 'text', 'name' => 'price', 'label' => 'Preço'],
                            ['type' => 'text', 'name' => 'period', 'label' => 'Período'],
                            ['type' => 'rich', 'name' => 'body', 'label' => 'Descrição'],
                            ['type' => 'text', 'name' => 'cta', 'label' => 'Botão'],
                            ['type' => 'text', 'name' => 'href', 'label' => 'Link do botão'],
                        ],
                    ],
                ],
            ],
            [
                'label' => 'Cases selecionados',
                'preview' => 'cases',
                'fields' => [
                    ['type' => 'text', 'name' => 'title', 'label' => 'Título'],
                    ['type' => 'cases', 'name' => 'items', 'label' => 'Cases (até 2)', 'max' => 2],
                    ['type' => 'select', 'name' => 'layout', 'label' => 'Layout', 'options' => [
                        ['value' => 'destaque', 'label' => 'Destaque (1 case, capa e 3 prints)'],
                        ['value' => 'par', 'label' => 'Par (2 cases lado a lado)'],
                    ]],
                ],
            ],
            [
                'label' => 'Lista de cases',
                'preview' => 'cases-list',
                'fields' => [
                    ['type' => 'text', 'name' => 'title', 'label' => 'Título'],
                    ['type' => 'rich', 'name' => 'intro', 'label' => 'Introdução'],
                    ['type' => 'toggle', 'name' => 'filter', 'label' => 'Exibir filtro por serviço'],
                ],
            ],
            [
                'label' => 'Chamada',
                'preview' => 'cta',
                'fields' => [
                    ['type' => 'text', 'name' => 'title', 'label' => 'Título'],
                    ['type' => 'rich', 'name' => 'body', 'label' => 'Texto'],
                    ['type' => 'text', 'name' => 'label', 'label' => 'Botão'],
                    ['type' => 'text', 'name' => 'href', 'label' => 'Link do botão'],
                    ['type' => 'text', 'name' => 'secondary_label', 'label' => 'Link secundário'],
                    ['type' => 'text', 'name' => 'secondary_href', 'label' => 'Endereço do link secundário'],
                ],
            ],
            [
                'label' => 'Formulário de contato',
                'preview' => 'contact',
                'fields' => [
                    ['type' => 'text', 'name' => 'title', 'label' => 'Título'],
                    ['type' => 'rich', 'name' => 'intro', 'label' => 'Introdução'],
                ],
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private static function itemsFields(bool $withLink): array
    {
        $item = [
            ['type' => 'text', 'name' => 'title', 'label' => 'Título'],
            ['type' => 'rich', 'name' => 'body', 'label' => 'Texto'],
        ];

        if ($withLink) {
            $item[] = ['type' => 'text', 'name' => 'href', 'label' => 'Link'];
        }

        return [
            ['type' => 'text', 'name' => 'title', 'label' => 'Título'],
            ['type' => 'rich', 'name' => 'intro', 'label' => 'Introdução'],
            ['type' => 'repeater', 'name' => 'items', 'label' => 'Itens', 'simple' => false, 'fields' => $item],
        ];
    }
}

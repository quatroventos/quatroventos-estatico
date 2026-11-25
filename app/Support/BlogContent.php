<?php

namespace App\Support;

/**
 * Conteúdo estático do blog (protótipo). Troca por CMS/WordPress depois.
 */
final class BlogContent
{
    public const WHATSAPP = 'https://wa.me/554130399593?text=Li%20o%20blog%20e%20quero%20falar%20sobre%20um%20projeto.';

    public const TOOL_PATH = '/como-aparecer-no-chatgpt';

    /**
     * @return list<array{slug: string, label: string, lead: string}>
     */
    public static function categories(): array
    {
        return [
            ['slug' => 'inteligencia-artificial', 'label' => 'Inteligência artificial', 'lead' => 'Agentes, RAG, LLMs e o que muda na operação quando a IA entra no processo.'],
            ['slug' => 'automacao', 'label' => 'Automação', 'lead' => 'Workflows, n8n e integrações que tiram trabalho repetitivo da equipe.'],
            ['slug' => 'desenvolvimento', 'label' => 'Desenvolvimento', 'lead' => 'Software sob medida, modernização e sistemas com IA embarcada.'],
            ['slug' => 'sites-e-seo', 'label' => 'Sites e SEO', 'lead' => 'Sites, WordPress, SEO e como o conteúdo aparece no Google e nas IAs.'],
            ['slug' => 'infraestrutura', 'label' => 'Infraestrutura', 'lead' => 'Hospedagem, sustentação e o que mantém o sistema no ar.'],
        ];
    }

    /**
     * @return list<array{slug: string, title: string, excerpt: string, category: string, date: string, body: list<array{type: string, text?: string, items?: list<string>}>}>
     */
    public static function posts(): array
    {
        return [
            [
                'slug' => 'o-que-e-empresa-ai-native',
                'title' => 'O que é uma empresa AI-native e o que muda na prática',
                'excerpt' => 'Não é instalar um chatbot. É decidir onde a IA entra no processo, com dado, regra e humano no ponto certo.',
                'category' => 'inteligencia-artificial',
                'date' => '2026-11-10',
                'body' => [
                    ['type' => 'p', 'text' => 'Uma empresa AI-native trata a inteligência artificial como parte do processo, não como um acessório na home. O ponto de partida é o fluxo real: quem dispara, que dado entra, que decisão a máquina pode tomar e onde a pessoa precisa revisar.'],
                    ['type' => 'p', 'text' => 'Na prática, isso muda três coisas. A operação deixa de depender só de formulário e planilha. O time passa a medir tempo de ciclo e exceções, não só “quantos leads entraram”. E o software passa a carregar modelos e regras de confiança, em vez de só CRUD.'],
                    ['type' => 'h2', 'text' => 'O que não é AI-native'],
                    ['type' => 'p', 'text' => 'Colocar um assistente genérico no WhatsApp sem acesso aos dados da empresa. Gerar texto de marketing sem amarrar ao catálogo. Comprar uma ferramenta e esperar que o processo se reorganize sozinho.'],
                    ['type' => 'h2', 'text' => 'Por onde começar'],
                    ['type' => 'ul', 'items' => [
                        'Escolher um processo com volume e regra clara.',
                        'Definir o dado que a IA pode ler e o que fica fora.',
                        'Marcar o limiar em que a resposta vai para humano.',
                        'Publicar o resultado no sistema que o time já usa.',
                    ]],
                    ['type' => 'p', 'text' => 'A Quatro Ventos monta esse recorte com a operação: agente, automação ou sistema sob medida, conforme o processo. Sem promessa de resultado inventado — o desenho sai do fluxo que você já roda.'],
                ],
            ],
            [
                'slug' => 'processos-com-potencial-para-ia',
                'title' => 'Como identificar os processos com maior potencial para IA',
                'excerpt' => 'Volume, regra repetível e dado disponível. Três filtros antes de falar em modelo.',
                'category' => 'inteligencia-artificial',
                'date' => '2026-11-04',
                'body' => [
                    ['type' => 'p', 'text' => 'O processo certo para IA já consome tempo da equipe, se repete com pouca variação e deixa rastros em e-mail, planilha ou sistema. Se falta dado ou a regra muda a cada caso, o primeiro passo é organizar a operação — não treinar modelo.'],
                    ['type' => 'h2', 'text' => 'Três filtros'],
                    ['type' => 'ul', 'items' => [
                        'Volume: acontece várias vezes por semana.',
                        'Regra: dá para escrever o critério em frases.',
                        'Dado: a informação já existe em algum lugar acessível.',
                    ]],
                    ['type' => 'p', 'text' => 'Com os três no verde, cabe um piloto: extrair, classificar, redigir rascunho ou roteirizar atendimento. Com um vermelho, o piloto vira retrabalho.'],
                ],
            ],
            [
                'slug' => 'agente-de-ia-ou-chatbot',
                'title' => 'Agente de IA ou chatbot: qual a diferença',
                'excerpt' => 'Chatbot responde. Agente age — com ferramentas, memória e limite de confiança.',
                'category' => 'inteligencia-artificial',
                'date' => '2026-10-28',
                'body' => [
                    ['type' => 'p', 'text' => 'Chatbot conversa. Agente usa ferramentas: consulta base, abre chamado, grava no CRM, dispara e-mail. A diferença útil para quem decide é o que acontece depois da resposta — se a conversa morre no balão ou se o processo anda.'],
                    ['type' => 'p', 'text' => 'Um agente bom tem escopo fechado, log do que fez e um caminho para humano quando a confiança cai. Sem isso, vira chatbot com nome novo.'],
                ],
            ],
            [
                'slug' => 'n8n-quando-usar',
                'title' => 'n8n: quando usar e quando partir para código',
                'excerpt' => 'Automação visual acelera o piloto. Código segura o processo crítico.',
                'category' => 'automacao',
                'date' => '2026-10-20',
                'body' => [
                    ['type' => 'p', 'text' => 'n8n funciona bem para conectar sistemas, orquestrar passos e validar um fluxo com a operação em dias, não meses. Quando o fluxo vira regra de negócio crítica, com versão, teste e ownership claro, o caminho natural é extrair partes para código e API.'],
                    ['type' => 'p', 'text' => 'A decisão não é “low-code versus engenharia”. É onde o custo de mudar a regra é menor — e quem responde quando quebra.'],
                ],
            ],
            [
                'slug' => 'rag-explicado-para-quem-decide',
                'title' => 'RAG explicado para quem decide',
                'excerpt' => 'A IA responde com trechos da base da empresa, com citação — não com chute genérico.',
                'category' => 'inteligencia-artificial',
                'date' => '2026-10-12',
                'body' => [
                    ['type' => 'p', 'text' => 'RAG (retrieval-augmented generation) busca trechos nos documentos da empresa e só então redige a resposta. O valor para a diretoria é controle: a máquina cita a fonte, e o time sabe o que entrou no contexto.'],
                    ['type' => 'p', 'text' => 'Sem base limpa e permissão clara, RAG amplifica confusão. Com base boa, reduz tempo de busca e alucinação em perguntas internas.'],
                ],
            ],
            [
                'slug' => 'checklist-manutencao-wordpress',
                'title' => 'Checklist de manutenção de WordPress',
                'excerpt' => 'Núcleo, plugins, backup e relatório. O mínimo para o site não virar incidente.',
                'category' => 'sites-e-seo',
                'date' => '2026-10-05',
                'body' => [
                    ['type' => 'p', 'text' => 'Manutenção de WordPress não é “atualizar quando der”. É rotina: núcleo e plugins com teste, backup restauroável, monitoramento e um relatório do que rodou no mês.'],
                    ['type' => 'ul', 'items' => [
                        'Atualizar núcleo, tema e plugins em janela controlada.',
                        'Conferir backup offsite e restaurar de tempos em tempos.',
                        'Revisar usuários, SSL e plugins abandonados.',
                        'Medir tempo de carregamento depois de cada ciclo.',
                    ]],
                ],
            ],
            [
                'slug' => 'custo-mensal-solucao-com-llm',
                'title' => 'Como calcular o custo mensal de uma solução com LLM',
                'excerpt' => 'Tokens, volume de conversa e humano no loop. A planilha antes do piloto.',
                'category' => 'desenvolvimento',
                'date' => '2026-09-28',
                'body' => [
                    ['type' => 'p', 'text' => 'O custo de LLM sobe com volume de chamadas, tamanho de contexto e modelo escolhido. Some a isso hospedagem, observabilidade e o tempo de quem revisa as exceções.'],
                    ['type' => 'p', 'text' => 'Antes do piloto, estime conversas ou documentos por mês, tokens médios de entrada e saída, e um teto de gasto. Sem teto, o experimento vira surpresa na fatura.'],
                ],
            ],
            [
                'slug' => 'lgpd-e-ia-generativa',
                'title' => 'LGPD e IA generativa: cuidados ao usar dados da empresa',
                'excerpt' => 'Base legal, minimização e o que não pode ir para o prompt.',
                'category' => 'inteligencia-artificial',
                'date' => '2026-09-18',
                'body' => [
                    ['type' => 'p', 'text' => 'Dado pessoal no prompt é tratamento. A equipe precisa saber a base legal, o tempo de retenção e se o provedor do modelo treina com o que recebe.'],
                    ['type' => 'p', 'text' => 'Na dúvida, anonimize, restrinja o escopo e registre o fluxo. IA sem governança de dado vira risco jurídico e operacional no mesmo pacote.'],
                ],
            ],
        ];
    }

    /**
     * @return array{slug: string, label: string, lead: string}|null
     */
    public static function category(string $slug): ?array
    {
        foreach (self::categories() as $category) {
            if ($category['slug'] === $slug) {
                return $category;
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function post(string $slug): ?array
    {
        foreach (self::posts() as $post) {
            if ($post['slug'] === $slug) {
                return $post;
            }
        }

        return null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function postsByCategory(string $slug): array
    {
        return array_values(array_filter(self::posts(), fn (array $post): bool => $post['category'] === $slug));
    }

    public static function categoryLabel(string $slug): string
    {
        return self::category($slug)['label'] ?? $slug;
    }

    /**
     * @return array<string, mixed>
     */
    public static function chromePayload(): array
    {
        return [
            'name' => 'Quatro Ventos',
            'logo' => asset('assets/logo-quatroventos.svg'),
            'whatsapp_url' => self::WHATSAPP,
            'home_path' => '/',
            'menus' => [
                'header' => [
                    ['label' => 'Serviços', 'href' => '/#servicos', 'children' => []],
                    ['label' => 'Blog', 'href' => '/blog', 'children' => []],
                    ['label' => 'Sobre', 'href' => '/sobre-a-quatro-ventos', 'children' => []],
                    ['label' => 'Contato', 'href' => '/contato', 'children' => []],
                ],
                'footer' => [],
            ],
            'seo' => [
                'og_site_name' => 'Quatro Ventos',
            ],
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\AiVisibilityReport;
use App\Models\Page;
use App\Services\PublicContent;
use App\Services\Seo\AiVisibilityAnalyzer;
use App\Services\SystemOptions;
use App\Support\Locales;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class AiVisibilityController extends Controller
{
    public function show(PublicContent $content, SystemOptions $options): View
    {
        return $this->page(null, $content, $options);
    }

    public function report(AiVisibilityReport $report, PublicContent $content, SystemOptions $options): View
    {
        return $this->page($report, $content, $options);
    }

    public function analyze(Request $request, AiVisibilityAnalyzer $analyzer): JsonResponse
    {
        if (filled($request->input('empresa_site'))) {
            return response()->json(['status' => 'ok']);
        }

        $data = $request->validate([
            'url' => ['required', 'string', 'max:500'],
        ]);

        try {
            $result = $analyzer->analyze($data['url']);
        } catch (RuntimeException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json(['message' => 'Não foi possível analisar agora. Tente de novo em instantes.'], 422);
        }

        $report = AiVisibilityReport::query()->create([
            'public_id' => AiVisibilityReport::mintId(),
            'url' => $result['url'],
            'host' => $result['host'],
            'score' => $result['score'],
            'band' => $result['band'],
            'niche' => $result['niche'],
            'checks' => $result['checks'],
            'fixes' => $result['fixes'],
            'response_ms' => $result['response_ms'],
            'ip' => $request->ip(),
        ]);

        Log::channel('ai-visibility')->info('ai_visibility.report_created', [
            'public_id' => $report->public_id,
            'url' => $report->url,
            'host' => $report->host,
            'score' => $report->score,
            'band' => $report->band,
            'niche' => $report->niche,
            'response_ms' => $report->response_ms,
            'ip' => $report->ip,
        ]);

        return response()->json([
            'id' => $report->public_id,
            'path' => $report->publicPath(),
            'score' => $report->score,
            'band' => $report->band,
        ]);
    }

    private function page(?AiVisibilityReport $report, PublicContent $content, SystemOptions $options): View
    {
        $locale = Locales::default();
        $site = $content->site($locale);
        $url = url('/'.AiVisibilityReport::PATH);
        $cms = $report ? null : Page::query()
            ->published()
            ->where('slug', AiVisibilityReport::PATH)
            ->where('locale', $locale)
            ->first();

        $title = $report
            ? 'Nota de '.$report->host.' na IA | Quatro Ventos'
            : ($cms?->seo_title ?: 'Como aparecer no ChatGPT? Teste gratuito do seu site | Quatro Ventos');
        $description = $report
            ? $report->host.' tirou '.$report->score.' de 100. Veja se o ChatGPT consegue ler o site e o que falta corrigir.'
            : ($cms?->seo_description ?: 'Como aparecer no ChatGPT: teste grátis se a IA consegue ler o seu site, entender o que você vende e citar o negócio. Sem cadastro.');

        $jsonld = $this->jsonLd($url, $title, $description, $site, $report);

        return view('site.ai-visibility', [
            'site' => $options->site(),
            'sitePayload' => $site,
            'report' => $report,
            'seo' => [
                'title' => $title,
                'description' => $description,
                'canonical' => $report ? url($report->publicPath()) : ($cms?->canonical ?: $url),
                'robots' => $cms?->robots ?: 'index, follow, max-image-preview:large',
                'og_site_name' => $site['seo']['og_site_name'] ?? 'Quatro Ventos',
                'og_locale' => 'pt_BR',
                'twitter_card' => $cms?->twitter_card ?: 'summary_large_image',
                'og_image' => $cms?->og_image ?: ($site['seo']['seo_og_image'] ?? null),
            ],
            'jsonld' => $jsonld,
            'updatedAt' => ($report?->created_at ?? $cms?->updated_at ?? now())->toIso8601String(),
            'analyzeUrl' => url('/api/'.AiVisibilityReport::PATH),
            'toolPath' => '/'.AiVisibilityReport::PATH,
        ]);
    }

    /**
     * @param  array<string, mixed>  $site
     * @return list<array<string, mixed>>
     */
    private function jsonLd(string $url, string $title, string $description, array $site, ?AiVisibilityReport $report): array
    {
        $home = url('/');
        $org = [
            '@context' => 'https://schema.org',
            '@type' => 'ProfessionalService',
            '@id' => $home.'#organization',
            'name' => $site['name'] ?? 'Quatro Ventos',
            'url' => $home,
            'logo' => $site['logo'] ?? asset('assets/logo-quatroventos.svg'),
        ];

        $app = [
            '@context' => 'https://schema.org',
            '@type' => 'WebApplication',
            '@id' => $url.'#app',
            'name' => 'Como aparecer no ChatGPT',
            'alternateName' => 'Meu site aparece na IA',
            'url' => $url,
            'description' => $description,
            'applicationCategory' => 'BusinessApplication',
            'operatingSystem' => 'Web',
            'inLanguage' => 'pt-BR',
            'isAccessibleForFree' => true,
            'offers' => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'BRL'],
            'provider' => ['@id' => $home.'#organization'],
        ];

        $page = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            '@id' => ($report ? url($report->publicPath()) : $url).'#webpage',
            'url' => $report ? url($report->publicPath()) : $url,
            'name' => $title,
            'description' => $description,
            'dateModified' => ($report?->created_at ?? now())->toIso8601String(),
            'isPartOf' => ['@id' => $home.'#website'],
            'about' => ['@id' => $home.'#organization'],
            'author' => ['@id' => $home.'#organization'],
        ];

        $article = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            '@id' => $url.'#article',
            'headline' => 'Como aparecer no ChatGPT?',
            'description' => $description,
            'inLanguage' => 'pt-BR',
            'dateModified' => ($report?->created_at ?? now())->toIso8601String(),
            'author' => ['@id' => $home.'#organization'],
            'publisher' => ['@id' => $home.'#organization'],
            'mainEntityOfPage' => ['@id' => $url.'#webpage'],
            'about' => [
                ['@type' => 'Thing', 'name' => 'ChatGPT'],
                ['@type' => 'Thing', 'name' => 'GEO'],
                ['@type' => 'Thing', 'name' => 'SEO para IA'],
            ],
        ];

        $howTo = [
            '@context' => 'https://schema.org',
            '@type' => 'HowTo',
            '@id' => $url.'#howto',
            'name' => 'Como aparecer no ChatGPT',
            'description' => 'Passos para o ChatGPT e outras IAs conseguirem ler e citar o seu negócio.',
            'inLanguage' => 'pt-BR',
            'totalTime' => 'PT60M',
            'step' => [
                ['@type' => 'HowToStep', 'name' => 'Deixar a IA entrar', 'text' => 'Peça para checar o robots.txt e não bloquear o ChatGPT e ferramentas parecidas.'],
                ['@type' => 'HowToStep', 'name' => 'Escrever a oferta na página inicial', 'text' => 'O texto principal precisa estar na página de verdade, não só depois de efeitos ou animações.'],
                ['@type' => 'HowToStep', 'name' => 'Deixar oferta e preço claros', 'text' => 'A home deve dizer o que a empresa faz, para quem é e quanto custa — ou como o orçamento funciona.'],
                ['@type' => 'HowToStep', 'name' => 'Adicionar dados do negócio em formato de máquina', 'text' => 'Quem cuida do site pode publicar JSON-LD descrevendo serviço, produto, FAQ ou empresa local.'],
                ['@type' => 'HowToStep', 'name' => 'Mostrar quem está por trás', 'text' => 'Publique página Sobre, data de atualização, resumo llms.txt e lista de páginas (sitemap).'],
            ],
        ];

        $faq = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            '@id' => $url.'#faq',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name' => 'Como aparecer no ChatGPT?',
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Deixe a IA entrar no site, escreva na página inicial o que você faz e quanto custa, use texto de verdade (não só imagem), e complete Sobre, resumo para IA e lista de páginas. Esta página testa exatamente isso.'],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Isso é a mesma coisa que aparecer no Google?',
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Não. No Google você disputa posição numa lista de links. No ChatGPT, a IA escreve uma resposta. O site só entra nessa resposta se a máquina conseguiu ler e entender o que está publicado. Este teste mede essa parte.'],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'O ChatGPT usa o meu site de verdade?',
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Quando está liberado, ele pode citar páginas públicas. Se as regras do site bloqueiam a IA ou a página inicial quase não tem texto, a chance de citação cai.'],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Vocês olham o site inteiro?',
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Não. O foco é a página inicial e os arquivos básicos da raiz. Páginas internas entram na lista de páginas e no resumo, mas a nota é da home.'],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'É de graça mesmo?',
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Sim. Sem cadastro. Você cola o endereço, recebe a nota e a lista do que corrigir. Nada é alterado no site. Fazer as correções é um serviço à parte, com orçamento.'],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Rodar o teste mexe no meu site?',
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Não. Só lemos o que já está público, como um visitante qualquer. Não instalamos nada e não acessamos área privada.'],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Quem faz essa ferramenta?',
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'A Quatro Ventos, de Curitiba: software, IA e sites. Se quiser ajuda para aplicar as correções, chame no WhatsApp.'],
                ],
            ],
        ];

        if ($report !== null) {
            return [$org, $app, $page, $faq];
        }

        return [$org, $app, $page, $article, $howTo, $faq];
    }
}

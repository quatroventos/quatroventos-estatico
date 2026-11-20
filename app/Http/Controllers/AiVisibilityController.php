<?php

namespace App\Http\Controllers;

use App\Models\AiVisibilityReport;
use App\Services\PublicContent;
use App\Services\Seo\AiVisibilityAnalyzer;
use App\Services\SystemOptions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

        return response()->json([
            'id' => $report->public_id,
            'path' => $report->publicPath(),
            'score' => $report->score,
            'band' => $report->band,
        ]);
    }

    private function page(?AiVisibilityReport $report, PublicContent $content, SystemOptions $options): View
    {
        $locale = 'pt-BR';
        $site = $content->site($locale);
        $url = url('/'.AiVisibilityReport::PATH);
        $title = $report
            ? 'Nota de '.$report->host.' na IA | Quatro Ventos'
            : 'Seu site aparece quando perguntam para a IA? | Quatro Ventos';
        $description = $report
            ? $report->host.' tirou '.$report->score.' de 100 na prontidão para ChatGPT, Claude e Perplexity. Veja o que falta para ser citado.'
            : 'Digite a URL e veja, em menos de um minuto, se ChatGPT, Claude e Perplexity conseguem ler e citar o seu negócio. Ferramenta gratuita da Quatro Ventos.';

        $jsonld = $this->jsonLd($url, $title, $description, $site, $report);

        return view('site.ai-visibility', [
            'site' => $options->site(),
            'sitePayload' => $site,
            'report' => $report,
            'seo' => [
                'title' => $title,
                'description' => $description,
                'canonical' => $report ? url($report->publicPath()) : $url,
                'robots' => 'index, follow, max-image-preview:large',
                'og_site_name' => $site['seo']['og_site_name'] ?? 'Quatro Ventos',
                'og_locale' => 'pt_BR',
                'twitter_card' => 'summary_large_image',
                'og_image' => $site['seo']['seo_og_image'] ?? null,
            ],
            'jsonld' => $jsonld,
            'updatedAt' => ($report?->created_at ?? now())->toIso8601String(),
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
            'name' => 'Meu site aparece na IA',
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

        $faq = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            '@id' => $url.'#faq',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name' => 'O que essa ferramenta mede?',
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Nove verificações que somam 100 pontos: robôs de IA no robots.txt, texto legível sem JavaScript, se a home diz o que é, para quem é e quanto custa, JSON-LD, HTTPS e tempo de resposta, título e description, llms.txt, autor/data/Sobre e sitemap.'],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'É gratuito?',
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Sim. A nota técnica é gratuita, sem cadastro. Nada é instalado no seu site.'],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'A análise olha o site inteiro?',
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'A nota lê a página inicial (home) e arquivos na raiz (robots.txt, llms.txt, sitemap.xml), do mesmo jeito que um robô de IA costuma começar.'],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'Quem faz a ferramenta?',
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'A Quatro Ventos, escritório de software, IA e sites em Curitiba. Se quiser que a gente aplique as correções no seu site, fale pelo WhatsApp.'],
                ],
            ],
        ];

        return [$org, $app, $page, $faq];
    }
}

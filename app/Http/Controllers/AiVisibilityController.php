<?php

namespace App\Http\Controllers;

use App\Models\AiVisibilityReport;
use App\Services\PublicContent;
use App\Services\Seo\AiVisibilityAnalyzer;
use App\Services\SystemOptions;
use App\Support\Locales;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class AiVisibilityController extends Controller
{
    public function report(AiVisibilityReport $report, PublicContent $content, SystemOptions $options): View
    {
        $locale = Locales::default();
        $site = $content->site($locale);
        $toolUrl = url('/'.AiVisibilityReport::PATH);
        $url = url($report->publicPath());
        $title = 'Nota de '.$report->host.' na IA | Quatro Ventos';
        $description = $report->host.' tirou '.$report->score.' de 100. Veja se o ChatGPT consegue ler o site e o que falta corrigir.';

        return view('site.ai-visibility', [
            'site' => $options->site(),
            'sitePayload' => $site,
            'report' => $report,
            'seo' => [
                'title' => $title,
                'description' => $description,
                'canonical' => $url,
                'robots' => 'noindex, follow',
                'og_site_name' => $site['seo']['og_site_name'] ?? 'Quatro Ventos',
                'og_locale' => 'pt_BR',
                'twitter_card' => 'summary_large_image',
                'og_image' => $site['seo']['seo_og_image'] ?? null,
            ],
            'jsonld' => $this->jsonLd($toolUrl, $url, $title, $description, $site, $report),
            'updatedAt' => $report->created_at?->toIso8601String() ?? now()->toIso8601String(),
            'analyzeUrl' => url('/api/'.AiVisibilityReport::PATH),
            'toolPath' => '/'.AiVisibilityReport::PATH,
        ]);
    }

    public function analyze(Request $request, AiVisibilityAnalyzer $analyzer): JsonResponse|RedirectResponse
    {
        if (filled($request->input('empresa_site'))) {
            return $this->analyzeReply($request, ['status' => 'ok']);
        }

        $data = $request->validate([
            'url' => ['required', 'string', 'max:500'],
        ]);

        try {
            $result = $analyzer->analyze($data['url']);
        } catch (RuntimeException $exception) {
            return $this->analyzeFailure($request, $exception->getMessage(), 422);
        } catch (Throwable $exception) {
            report($exception);

            return $this->analyzeFailure($request, 'Não foi possível analisar agora. Tente de novo em instantes.', 422);
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

        return $this->analyzeReply($request, [
            'id' => $report->public_id,
            'path' => $report->publicPath(),
            'score' => $report->score,
            'band' => $report->band,
        ], $report);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function analyzeReply(Request $request, array $payload, ?AiVisibilityReport $report = null): JsonResponse|RedirectResponse
    {
        if ($this->wantsJsonAnalyze($request)) {
            return response()->json($payload);
        }

        if ($report !== null) {
            return redirect($report->publicPath());
        }

        return redirect('/'.AiVisibilityReport::PATH);
    }

    private function analyzeFailure(Request $request, string $message, int $status): JsonResponse|RedirectResponse
    {
        if ($this->wantsJsonAnalyze($request)) {
            return response()->json(['message' => $message], $status);
        }

        return redirect('/'.AiVisibilityReport::PATH)
            ->withInput($request->only('url'))
            ->withErrors(['url' => $message]);
    }

    private function wantsJsonAnalyze(Request $request): bool
    {
        return $request->expectsJson()
            || $request->ajax()
            || $request->header('X-Requested-With') === 'XMLHttpRequest';
    }

    /**
     * @param  array<string, mixed>  $site
     * @return list<array<string, mixed>>
     */
    private function jsonLd(string $toolUrl, string $url, string $title, string $description, array $site, AiVisibilityReport $report): array
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
            '@id' => $toolUrl.'#app',
            'name' => 'Como aparecer no ChatGPT',
            'alternateName' => 'Meu site aparece na IA',
            'url' => $toolUrl,
            'description' => 'Checklist e nota gratuita de prontidão para ChatGPT e outras IAs.',
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
            '@id' => $url.'#webpage',
            'url' => $url,
            'name' => $title,
            'description' => $description,
            'dateModified' => $report->created_at?->toIso8601String() ?? now()->toIso8601String(),
            'isPartOf' => ['@id' => $home.'#website'],
            'about' => ['@id' => $home.'#organization'],
            'author' => ['@id' => $home.'#organization'],
        ];

        return [$org, $app, $page];
    }
}

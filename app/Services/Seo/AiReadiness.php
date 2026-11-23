<?php

namespace App\Services\Seo;

/**
 * Prontidão do site público para buscadores de IA — mesmos 15 critérios
 * do teste “Como aparecer no ChatGPT?” (is-agentic / domínio).
 *
 * @phpstan-import-type Check from SeoAnalyzer
 */
class AiReadiness
{
    public const AI_BOTS = ['GPTBot', 'OAI-SearchBot', 'ChatGPT-User', 'ClaudeBot', 'PerplexityBot', 'Google-Extended'];

    public function __construct(private AiVisibilityAnalyzer $analyzer) {}

    /**
     * @return array{score: int, checks: list<Check>}
     */
    public function site(): array
    {
        $url = rtrim((string) config('app.url'), '/');
        if ($url === '') {
            $url = 'https://quatroventos.com.br';
        }

        try {
            $report = $this->analyzer->analyze($url);
        } catch (\Throwable $exception) {
            return [
                'score' => 0,
                'checks' => [
                    $this->check(
                        'fetch',
                        'Leitura do site público',
                        'bad',
                        $exception->getMessage() !== '' ? $exception->getMessage() : 'Não foi possível analisar o site público.',
                        1
                    ),
                ],
            ];
        }

        return [
            'score' => $report['score'],
            'checks' => array_map(fn (array $check): array => [
                'id' => $check['id'],
                'label' => $check['label'],
                'status' => $check['status'],
                'message' => $check['message'],
                'weight' => max(1, (int) $check['max']),
                'fix' => null,
            ], $report['checks']),
        ];
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
     * @param  'good'|'ok'|'bad'  $status
     * @return Check
     */
    private function check(string $id, string $label, string $status, string $message, int $weight): array
    {
        return ['id' => $id, 'label' => $label, 'status' => $status, 'message' => $message, 'weight' => $weight, 'fix' => null];
    }
}

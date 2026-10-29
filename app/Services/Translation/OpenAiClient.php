<?php

namespace App\Services\Translation;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class OpenAiClient
{
    public function configured(): bool
    {
        return filled(config('services.openai.key'));
    }

    /**
     * @param  array<string, mixed>  $schema
     * @return array<string, mixed>
     */
    public function json(string $system, string $user, string $schemaName, array $schema): array
    {
        if (! $this->configured()) {
            throw new RuntimeException('OPENAI_API_KEY não configurada no .env.');
        }

        $response = Http::withToken((string) config('services.openai.key'))
            ->acceptJson()
            ->timeout((int) config('services.openai.timeout', 120))
            ->retry(2, 1500, throw: false)
            ->post(rtrim((string) config('services.openai.base_url'), '/').'/chat/completions', [
                'model' => (string) config('services.openai.model'),
                'temperature' => 0.2,
                'messages' => [
                    ['role' => 'system', 'content' => $system],
                    ['role' => 'user', 'content' => $user],
                ],
                'response_format' => [
                    'type' => 'json_schema',
                    'json_schema' => [
                        'name' => $schemaName,
                        'strict' => true,
                        'schema' => $schema,
                    ],
                ],
            ]);

        if ($response->failed()) {
            $message = (string) ($response->json('error.message') ?? $response->body());

            throw new RuntimeException('OpenAI '.$response->status().': '.mb_substr($message, 0, 300));
        }

        $content = $response->json('choices.0.message.content');
        $decoded = is_string($content) ? json_decode($content, true) : null;

        if (! is_array($decoded)) {
            throw new RuntimeException('Resposta da OpenAI fora do formato esperado.');
        }

        return $decoded;
    }
}

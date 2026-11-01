<?php

namespace App\Services\WhatsApp;

use App\Jobs\SendWhatsAppMessageJob;
use App\Models\SiteSetting;

/**
 * Avisos de WhatsApp para envios de formulário: um para o número do escritório e uma
 * confirmação para o visitante. Cada mensagem vira um job próprio, para que a falha de
 * uma não reenvie a outra. Com $confirm, o aviso do escritório sai na hora, para saber
 * se chegou quando o e-mail falhou.
 */
class ContactWhatsApp
{
    public const DEFAULT_ADMIN_TEMPLATE = "Novo contato pelo site\n\nNome: {nome}\nE-mail: {email}\nWhatsApp: {telefone}\nDestino: {destino}\n\n{mensagem}";

    public const DEFAULT_VISITOR_TEMPLATE = 'Olá, {nome}! Recebemos sua mensagem pelo site da {site} e vamos responder em breve.';

    public const VARIABLES = ['nome', 'email', 'telefone', 'destino', 'mensagem', 'pagina', 'site'];

    /**
     * @param  array<string, string|null>  $variables
     * @return bool se o aviso do escritório foi enviado (ou enfileirado, sem $confirm)
     */
    public function notify(array $variables, bool $confirm = false): bool
    {
        $settings = SiteSetting::query()->first();
        $client = $settings !== null ? EvolutionClient::fromSettings($settings) : null;

        if ($settings === null || ! $settings->whatsapp_enabled || ! $client->configured()) {
            return false;
        }

        $variables['site'] = (string) ($settings->name ?: config('app.name'));

        $adminNumber = self::normalizeNumber($settings->whatsapp_notify_number);

        $notified = false;

        if ($adminNumber !== null) {
            $text = self::render($settings->whatsapp_admin_template ?: self::DEFAULT_ADMIN_TEMPLATE, $variables);

            if ($confirm) {
                try {
                    $client->sendText($adminNumber, $text);
                    $notified = true;
                } catch (\Throwable $e) {
                    report($e);
                }
            } else {
                SendWhatsAppMessageJob::dispatch($adminNumber, $text);
                $notified = true;
            }
        }

        $visitorNumber = self::normalizeNumber($variables['telefone'] ?? null);

        if ($visitorNumber !== null && $visitorNumber !== $adminNumber) {
            SendWhatsAppMessageJob::dispatch($visitorNumber, self::render(
                $settings->whatsapp_visitor_template ?: self::DEFAULT_VISITOR_TEMPLATE,
                $variables,
            ));
        }

        return $notified;
    }

    /**
     * @param  array<string, string|null>  $variables
     */
    public static function render(string $template, array $variables): string
    {
        $replacements = [];

        foreach (self::VARIABLES as $name) {
            $value = trim((string) ($variables[$name] ?? ''));
            $replacements['{'.$name.'}'] = $value !== '' ? $value : '-';
        }

        return trim(strtr($template, $replacements));
    }

    /**
     * Só dígitos, com DDI 55 quando o número vier no formato nacional (DDD + número).
     */
    public static function normalizeNumber(?string $number): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $number) ?? '';
        $digits = ltrim($digits, '0');

        if (strlen($digits) === 10 || strlen($digits) === 11) {
            $digits = '55'.$digits;
        }

        return strlen($digits) >= 12 && strlen($digits) <= 15 ? $digits : null;
    }
}

<?php

namespace App\Http\Controllers;

use App\Cms\FormDefinition;
use App\Mail\ContactMessageMail;
use App\Models\ContactSubmission;
use App\Models\FormOption;
use App\Models\SiteSetting;
use App\Services\MailSettings;
use App\Services\WhatsApp\ContactWhatsApp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class SiteFormController extends Controller
{
    public function contact(Request $request, MailSettings $mailSettings, ContactWhatsApp $whatsApp): RedirectResponse|JsonResponse
    {
        $json = $request->expectsJson();

        if (filled($request->input('empresa_site'))) {
            return $this->contactResult('enviado', $json);
        }

        try {
            $data = $request->validate(FormDefinition::validationRules('contact'));
        } catch (ValidationException $exception) {
            if ($json) {
                throw $exception;
            }

            return $this->contactRedirect('invalido')
                ->withErrors($exception->errors())
                ->withInput();
        }

        $submission = ContactSubmission::query()->create(
            FormDefinition::submissionAttributes('contact', $data)
        );

        $this->notifyWhatsApp($whatsApp, $submission);

        $recipient = $this->contactRecipient((string) ($data['destino'] ?? ''));

        if ($recipient === null) {
            report(new \RuntimeException('Contato sem destinatário configurado.'));

            return $this->contactResult('enviado', $json);
        }

        $mailSettings->apply();

        try {
            Mail::to($recipient)->send(new ContactMessageMail(
                $submission->name,
                $submission->email,
                $submission->destination,
                $this->mailFields($data),
            ));
        } catch (\Throwable $e) {
            report($e);
        }

        return $this->contactResult('enviado', $json);
    }

    public function lead(Request $request, MailSettings $mailSettings, ContactWhatsApp $whatsApp): JsonResponse
    {
        if (filled($request->input('empresa_site'))) {
            return response()->json(['status' => 'enviado']);
        }

        $data = $request->validate([
            'nome' => ['required', 'string', 'max:180'],
            'whatsapp' => ['required', 'string', 'max:40', 'regex:/^[\d\s()+.-]{8,}$/'],
            'pagina' => ['nullable', 'string', 'max:255'],
        ]);

        $page = (string) ($data['pagina'] ?? '');
        $destination = 'Lead rápido';

        $submission = ContactSubmission::query()->create([
            'name' => $data['nome'],
            'email' => '',
            'phone' => $data['whatsapp'],
            'destination' => $destination,
            'message' => $page !== '' ? "Tenho interesse (formulário rápido em {$page})." : 'Tenho interesse (formulário rápido).',
            'extra' => $page !== '' ? ['pagina' => ['label' => 'Página', 'value' => $page]] : null,
        ]);

        $this->notifyWhatsApp($whatsApp, $submission, $page);

        $recipient = $this->contactRecipient('');

        if ($recipient === null) {
            report(new \RuntimeException('Lead sem destinatário configurado.'));

            return response()->json(['status' => 'enviado']);
        }

        $mailSettings->apply();

        try {
            Mail::to($recipient)->send(new ContactMessageMail(
                $submission->name,
                $submission->email,
                $destination,
                [
                    'Nome' => $submission->name,
                    'WhatsApp' => $submission->phone,
                    'Página' => $page,
                ],
            ));
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json(['status' => 'enviado']);
    }

    private function notifyWhatsApp(ContactWhatsApp $whatsApp, ContactSubmission $submission, string $page = ''): void
    {
        try {
            $whatsApp->notify([
                'nome' => $submission->name,
                'email' => $submission->email,
                'telefone' => $submission->phone,
                'destino' => $submission->destination,
                'mensagem' => $submission->message,
                'pagina' => $page,
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private function contactResult(string $status, bool $json, int $code = 200): RedirectResponse|JsonResponse
    {
        if ($json) {
            return response()->json(['status' => $status], $code);
        }

        return $this->contactRedirect($status);
    }

    private function contactRedirect(string $status): RedirectResponse
    {
        return redirect()->to(url('/contato').'?status='.rawurlencode($status).'#contact-form-title');
    }

    private function contactRecipient(string $destination): ?string
    {
        if ($destination !== '') {
            $optionEmail = FormOption::query()
                ->where('form', 'contact')
                ->where('field', 'destino')
                ->where('value', $destination)
                ->value('email');

            if (is_string($optionEmail) && filter_var($optionEmail, FILTER_VALIDATE_EMAIL)) {
                return $optionEmail;
            }
        }

        return $this->siteContactEmail() ?? (
            is_string(config('mail.from.address')) && filter_var(config('mail.from.address'), FILTER_VALIDATE_EMAIL)
                ? config('mail.from.address')
                : null
        );
    }

    private function siteContactEmail(): ?string
    {
        $email = SiteSetting::query()->value('email');

        return is_string($email) && filter_var($email, FILTER_VALIDATE_EMAIL)
            ? $email
            : null;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, string>
     */
    private function mailFields(array $data): array
    {
        $fields = [];

        foreach (FormDefinition::fields('contact') as $field) {
            $fieldName = $field['name'] ?? '';

            if ($fieldName === '' || $fieldName === 'lgpd' || ($field['type'] ?? '') === 'file') {
                continue;
            }

            $value = $data[$fieldName] ?? '';
            $fields[(string) ($field['label'] ?? $fieldName)] = is_scalar($value) ? (string) $value : '';
        }

        return $fields;
    }
}

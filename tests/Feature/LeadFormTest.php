<?php

namespace Tests\Feature;

use App\Mail\ContactMessageMail;
use App\Models\ContactSubmission;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class LeadFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_lead_is_stored_and_mailed_to_site_email(): void
    {
        Mail::fake();
        SiteSetting::query()->create(['name' => 'Quatro Ventos', 'email' => 'contato@quatroventos.com.br']);

        $this->postJson('/api/contato/lead', [
            'nome' => 'Ana',
            'whatsapp' => '(41) 99999-0000',
            'pagina' => '/agentes-de-ia',
        ])->assertOk()->assertJsonPath('status', 'enviado');

        $row = ContactSubmission::query()->sole();
        $this->assertSame('(41) 99999-0000', $row->phone);
        $this->assertSame('Lead rápido', $row->destination);
        $this->assertStringContainsString('/agentes-de-ia', $row->message);

        Mail::assertSent(ContactMessageMail::class, fn (ContactMessageMail $mail) => $mail->hasTo('contato@quatroventos.com.br'));
    }

    public function test_mail_failure_falls_back_to_a_direct_whatsapp_notice(): void
    {
        $this->settingsWithWhatsApp();
        Queue::fake();
        Http::fake(['evo.example.com/*' => Http::response(['key' => ['id' => 'msg_1']])]);
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP fora do ar'));

        $this->postJson('/api/contato/lead', ['nome' => 'Ana', 'whatsapp' => '(41) 99999-0000'])
            ->assertOk()
            ->assertJsonPath('status', 'enviado');

        Http::assertSent(fn (Request $request) => str_contains($request->url(), '/message/sendText/site')
            && $request['number'] === '5541988887777');
        $this->assertSame(1, ContactSubmission::query()->count());
    }

    public function test_lead_fails_when_mail_and_whatsapp_both_fail(): void
    {
        $this->settingsWithWhatsApp();
        Queue::fake();
        Http::fake(['evo.example.com/*' => Http::response(['message' => 'instance offline'], 500)]);
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP fora do ar'));

        $this->postJson('/api/contato/lead', ['nome' => 'Ana', 'whatsapp' => '(41) 99999-0000'])
            ->assertStatus(503)
            ->assertJsonPath('status', 'erro');

        $this->assertSame(1, ContactSubmission::query()->count());
    }

    public function test_lead_requires_name_and_whatsapp(): void
    {
        $this->postJson('/api/contato/lead', ['nome' => '', 'whatsapp' => 'abc'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['nome', 'whatsapp']);

        $this->assertSame(0, ContactSubmission::query()->count());
    }

    private function settingsWithWhatsApp(): void
    {
        SiteSetting::query()->create([
            'name' => 'Quatro Ventos',
            'email' => 'contato@quatroventos.com.br',
            'whatsapp_enabled' => true,
            'evolution_url' => 'https://evo.example.com',
            'evolution_api_key' => 'evo-key',
            'evolution_instance' => 'site',
            'whatsapp_notify_number' => '(41) 98888-7777',
        ]);
    }

    public function test_honeypot_is_ignored(): void
    {
        $this->postJson('/api/contato/lead', ['empresa_site' => 'bot', 'nome' => 'x'])
            ->assertOk()
            ->assertJsonPath('status', 'enviado');

        $this->assertSame(0, ContactSubmission::query()->count());
    }
}

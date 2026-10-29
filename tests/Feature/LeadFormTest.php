<?php

namespace Tests\Feature;

use App\Mail\ContactMessageMail;
use App\Models\ContactSubmission;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
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

    public function test_lead_requires_name_and_whatsapp(): void
    {
        $this->postJson('/api/contato/lead', ['nome' => '', 'whatsapp' => 'abc'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['nome', 'whatsapp']);

        $this->assertSame(0, ContactSubmission::query()->count());
    }

    public function test_honeypot_is_ignored(): void
    {
        $this->postJson('/api/contato/lead', ['empresa_site' => 'bot', 'nome' => 'x'])
            ->assertOk()
            ->assertJsonPath('status', 'enviado');

        $this->assertSame(0, ContactSubmission::query()->count());
    }
}

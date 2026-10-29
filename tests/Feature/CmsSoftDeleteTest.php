<?php

namespace Tests\Feature;

use App\Models\ContactSubmission;
use App\Models\TeamMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsSoftDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_member_can_be_soft_deleted_and_restored(): void
    {
        $admin = $this->makeAdmin();
        $member = TeamMember::query()->create([
            'name' => 'Ana',
            'role' => 'Sócia',
            'sort' => 1,
        ]);

        $this->actingAs($admin)
            ->deleteJson("/gestao/api/team-members/{$member->id}")
            ->assertNoContent();

        $this->assertSoftDeleted('team_members', ['id' => $member->id]);

        $this->actingAs($admin)
            ->getJson('/gestao/api/team-members')
            ->assertOk()
            ->assertJsonCount(0, 'data');

        $this->actingAs($admin)
            ->getJson('/gestao/api/team-members?trashed=1')
            ->assertOk()
            ->assertJsonPath('data.0.id', $member->id);

        $this->actingAs($admin)
            ->postJson("/gestao/api/team-members/{$member->id}/restore")
            ->assertOk()
            ->assertJsonPath('data.id', $member->id);

        $this->assertDatabaseHas('team_members', ['id' => $member->id, 'deleted_at' => null]);
    }

    public function test_contact_submission_can_be_soft_deleted_and_restored(): void
    {
        $admin = $this->makeAdmin();
        $item = ContactSubmission::query()->create([
            'name' => 'Cliente',
            'email' => 'cliente@example.com',
            'phone' => '11999999999',
            'destination' => 'Geral',
            'message' => 'Olá',
        ]);

        $this->actingAs($admin)
            ->deleteJson("/gestao/api/messages/{$item->id}")
            ->assertNoContent();

        $this->assertSoftDeleted('contact_submissions', ['id' => $item->id]);

        $this->actingAs($admin)
            ->postJson("/gestao/api/messages/{$item->id}/restore")
            ->assertOk()
            ->assertJsonPath('data.id', $item->id);

        $this->assertDatabaseHas('contact_submissions', ['id' => $item->id, 'deleted_at' => null]);
    }

    public function test_user_can_be_soft_deleted_and_restored(): void
    {
        $admin = $this->makeAdmin();
        $other = $this->makeEditor(['email' => 'editor@example.com']);

        $this->actingAs($admin)
            ->deleteJson("/gestao/api/users/{$other->id}")
            ->assertNoContent();

        $this->assertSoftDeleted('users', ['id' => $other->id]);

        $this->actingAs($admin)
            ->getJson('/gestao/api/users?trashed=1')
            ->assertOk()
            ->assertJsonPath('data.0.id', $other->id);

        $this->actingAs($admin)
            ->postJson("/gestao/api/users/{$other->id}/restore")
            ->assertOk()
            ->assertJsonPath('data.id', $other->id);

        $this->assertDatabaseHas('users', ['id' => $other->id, 'deleted_at' => null]);
    }

    public function test_user_restore_rejects_email_conflict(): void
    {
        $admin = $this->makeAdmin();
        $other = $this->makeEditor(['email' => 'editor@example.com']);

        $this->actingAs($admin)
            ->deleteJson("/gestao/api/users/{$other->id}")
            ->assertNoContent();

        $this->actingAs($admin)
            ->postJson('/gestao/api/users', [
                'name' => 'Novo',
                'email' => 'editor@example.com',
                'password' => 'password123',
                'role' => 'user',
            ])
            ->assertCreated();

        $this->actingAs($admin)
            ->postJson("/gestao/api/users/{$other->id}/restore")
            ->assertStatus(422);
    }
}

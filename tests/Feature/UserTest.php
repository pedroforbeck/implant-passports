<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private function userPayload(array $overrides = []): array
    {
        return array_replace([
            'name' => 'Dr. Renato Lima',
            'email' => 'renato@passaporte.test',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
            'role' => Role::Doctor->value,
        ], $overrides);
    }

    public function test_admin_can_access_create_user_form(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('users.create'))
            ->assertOk();
    }

    public function test_admin_can_create_user(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('users.store'), $this->userPayload())
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'Dr. Renato Lima',
            'email' => 'renato@passaporte.test',
            'role' => Role::Doctor->value,
        ]);
    }

    public function test_duplicate_email_is_rejected_when_creating_user(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->create(['email' => 'renato@passaporte.test']);

        $this->actingAs($admin)
            ->post(route('users.store'), $this->userPayload())
            ->assertSessionHasErrors('email');
    }

    public function test_doctor_cannot_create_user(): void
    {
        $doctor = User::factory()->doctor()->create();

        $this->actingAs($doctor)
            ->get(route('users.create'))
            ->assertForbidden();

        $this->actingAs($doctor)
            ->post(route('users.store'), $this->userPayload())
            ->assertForbidden();

        $this->assertDatabaseMissing('users', ['email' => 'renato@passaporte.test']);
    }

    public function test_patient_account_cannot_create_user(): void
    {
        $patient = User::factory()->patient()->create();

        $this->actingAs($patient)
            ->post(route('users.store'), $this->userPayload())
            ->assertForbidden();

        $this->assertDatabaseMissing('users', ['email' => 'renato@passaporte.test']);
    }
}
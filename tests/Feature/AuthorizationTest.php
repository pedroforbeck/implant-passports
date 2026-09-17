<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_admin_can_manage_manufacturers(): void
    {
        $admin = User::factory()->admin()->create();
        $doctor = User::factory()->doctor()->create();
        $patient = User::factory()->patient()->create();

        $this->actingAs($patient)->get(route('manufacturers.index'))->assertForbidden();
        $this->actingAs($doctor)->get(route('manufacturers.index'))->assertForbidden();
        $this->actingAs($admin)->get(route('manufacturers.index'))->assertOk();
    }

    public function test_only_admin_can_manage_users(): void
    {
        $admin = User::factory()->admin()->create();
        $doctor = User::factory()->doctor()->create();

        $this->actingAs($doctor)->get(route('users.index'))->assertForbidden();
        $this->actingAs($admin)->get(route('users.index'))->assertOk();
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get(route('patients.index'))->assertRedirect(route('login'));
    }
}

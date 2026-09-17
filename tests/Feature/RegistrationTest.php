<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_user_with_patient_role_by_default(): void
    {
        $this->post('/register', [
            'name' => 'Novo Paciente',
            'email' => 'novo@passaporte.test',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'novo@passaporte.test',
            'role' => Role::Patient->value,
        ]);
    }

    public function test_guest_sees_registration_form(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Cadastrar')
            ->assertSee('perfil');
    }

    public function test_registered_patient_has_no_passport_linked_until_doctor_links_it(): void
    {
        $this->post('/register', [
            'name' => 'Novo Paciente',
            'email' => 'novo@passaporte.test',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ])->assertRedirect(route('dashboard'));

        $user = User::query()->where('email', 'novo@passaporte.test')->firstOrFail();

        $this->assertNull($user->patientProfile);
    }
}
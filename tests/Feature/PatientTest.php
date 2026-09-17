<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctor_can_view_list_of_own_patients(): void
    {
        $doctor = User::factory()->doctor()->create();
        Patient::factory(2)->for($doctor, 'doctor')->create();

        $this->actingAs($doctor)
            ->get(route('patients.index'))
            ->assertOk()
            ->assertSee($doctor->patients->first()->name);
    }

    public function test_doctor_cannot_see_patients_of_another_doctor(): void
    {
        $doctor = User::factory()->doctor()->create();
        $other = User::factory()->doctor()->create();

        Patient::factory()->for($doctor, 'doctor')->create(['name' => 'Paciente Próprio']);
        Patient::factory()->for($other, 'doctor')->create(['name' => 'Paciente de Outro Médico']);

        $this->actingAs($doctor)
            ->get(route('patients.index'))
            ->assertSee('Paciente Próprio')
            ->assertDontSee('Paciente de Outro Médico');
    }

    public function test_doctor_can_create_patient_bound_to_themselves(): void
    {
        $doctor = User::factory()->doctor()->create();
        $other = User::factory()->doctor()->create();

        $this->actingAs($doctor)
            ->post(route('patients.store'), [
                'name' => 'João da Silva',
                'cpf' => '123.456.789-09',
                'birth_date' => '1990-05-10',
                'blood_type' => 'O+',
                'phone' => '(11) 99999-0000',
                'doctor_id' => $other->id,
                'notes' => 'Observação de teste.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('patients', [
            'cpf' => '123.456.789-09',
            'name' => 'João da Silva',
            'doctor_id' => $doctor->id,
        ]);
    }

    public function test_invalid_cpf_is_rejected(): void
    {
        $doctor = User::factory()->doctor()->create();

        $this->actingAs($doctor)
            ->post(route('patients.store'), [
                'name' => 'Maria Oliveira',
                'cpf' => '1234',
                'birth_date' => '1990-05-10',
            ])
            ->assertSessionHasErrors('cpf');
    }

    public function test_doctor_can_edit_own_patient(): void
    {
        $doctor = User::factory()->doctor()->create();
        $patient = Patient::factory()->for($doctor, 'doctor')->create();

        $this->actingAs($doctor)
            ->get(route('patients.edit', $patient))
            ->assertOk();

        $this->actingAs($doctor)
            ->put(route('patients.update', $patient), [
                'name' => 'Nome Atualizado',
                'cpf' => $patient->cpf,
                'birth_date' => $patient->birth_date->format('Y-m-d'),
                'blood_type' => 'AB-',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'name' => 'Nome Atualizado',
            'blood_type' => 'AB-',
        ]);
    }

    public function test_doctor_cannot_edit_patient_of_another_doctor(): void
    {
        $doctor = User::factory()->doctor()->create();
        $other = User::factory()->doctor()->create();
        $patient = Patient::factory()->for($other, 'doctor')->create();

        $this->actingAs($doctor)
            ->get(route('patients.edit', $patient))
            ->assertForbidden();
    }

    public function test_patient_account_can_view_own_passport_but_not_the_list(): void
    {
        $doctor = User::factory()->doctor()->create();
        $account = User::factory()->patient()->create();
        $patient = Patient::factory()->for($doctor, 'doctor')->for($account, 'user')->create();

        $this->actingAs($account)
            ->get(route('patients.index'))
            ->assertForbidden();

        $this->actingAs($account)
            ->get(route('patients.show', $patient))
            ->assertOk()
            ->assertSee($patient->name);
    }

    public function test_admin_can_access_patient_creation_form(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('patients.create'))
            ->assertOk();
    }
}

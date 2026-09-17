<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\Manufacturer;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeviceTest extends TestCase
{
    use RefreshDatabase;

    private function devicePayload(array $overrides = []): array
    {
        return array_replace([
            'manufacturer_id' => Manufacturer::factory()->create()->id,
            'type' => 'marcapasso',
            'model' => 'Advisa CRM',
            'serial_number' => 'SN-TEST-001',
            'implanted_at' => '2024-03-01',
            'hospital' => 'Hospital Teste',
            'mri_conditional' => '1',
            'status' => 'ativo',
            'notes' => null,
        ], $overrides);
    }

    public function test_doctor_can_create_device_for_own_patient(): void
    {
        $doctor = User::factory()->doctor()->create();
        $patient = Patient::factory()->for($doctor, 'doctor')->create();

        $this->actingAs($doctor)
            ->post(route('patients.devices.store', $patient), $this->devicePayload())
            ->assertRedirect();

        $this->assertDatabaseHas('devices', [
            'patient_id' => $patient->id,
            'serial_number' => 'SN-TEST-001',
            'mri_conditional' => true,
        ]);
    }

    public function test_doctor_cannot_create_device_for_other_doctors_patient(): void
    {
        $doctor = User::factory()->doctor()->create();
        $other = User::factory()->doctor()->create();
        $patient = Patient::factory()->for($other, 'doctor')->create();

        $this->actingAs($doctor)
            ->post(route('patients.devices.store', $patient), $this->devicePayload())
            ->assertForbidden();
    }

    public function test_duplicate_serial_number_is_rejected(): void
    {
        $doctor = User::factory()->doctor()->create();
        $patient = Patient::factory()->for($doctor, 'doctor')->create();

        Device::factory()->for($patient)->create(['serial_number' => 'SN-TEST-001']);

        $this->actingAs($doctor)
            ->post(route('patients.devices.store', $patient), $this->devicePayload())
            ->assertSessionHasErrors('serial_number');
    }

    public function test_doctor_sees_only_devices_of_own_patients(): void
    {
        $doctor = User::factory()->doctor()->create();
        $other = User::factory()->doctor()->create();

        Device::factory()->for(Patient::factory()->for($doctor, 'doctor'))->create(['serial_number' => 'SN-MEU-0001']);
        Device::factory()->for(Patient::factory()->for($other, 'doctor'))->create(['serial_number' => 'SN-OUTRO-0001']);

        $this->actingAs($doctor)
            ->get(route('devices.index'))
            ->assertOk()
            ->assertSee('SN-MEU-0001')
            ->assertDontSee('SN-OUTRO-0001');
    }

    public function test_doctor_can_access_create_device_form_for_own_patient(): void
    {
        $doctor = User::factory()->doctor()->create();
        $patient = Patient::factory()->for($doctor, 'doctor')->create();

        $this->actingAs($doctor)
            ->get(route('patients.devices.create', $patient))
            ->assertOk()
            ->assertSee($patient->name);
    }

    public function test_doctor_can_edit_device_of_own_patient(): void
    {
        $doctor = User::factory()->doctor()->create();
        $patient = Patient::factory()->for($doctor, 'doctor')->create();
        $device = Device::factory()->for($patient)->create();

        $this->actingAs($doctor)
            ->put(route('devices.update', $device), $this->devicePayload([
                'serial_number' => $device->serial_number,
                'model' => 'Modelo Novo',
                'mri_conditional' => '0',
            ]))
            ->assertRedirect();

        $this->assertDatabaseHas('devices', [
            'id' => $device->id,
            'model' => 'Modelo Novo',
            'mri_conditional' => false,
        ]);
    }

    public function test_doctor_can_view_device_details(): void
    {
        $doctor = User::factory()->doctor()->create();
        $patient = Patient::factory()->for($doctor, 'doctor')->create();
        $device = Device::factory()->for($patient)->create();

        $this->actingAs($doctor)
            ->get(route('devices.show', $device))
            ->assertOk()
            ->assertSee($device->serial_number);
    }

    public function test_doctor_can_access_edit_device_form(): void
    {
        $doctor = User::factory()->doctor()->create();
        $patient = Patient::factory()->for($doctor, 'doctor')->create();
        $device = Device::factory()->for($patient)->create();

        $this->actingAs($doctor)
            ->get(route('devices.edit', $device))
            ->assertOk();
    }

    public function test_doctor_cannot_edit_device_of_other_doctors_patient(): void
    {
        $doctor = User::factory()->doctor()->create();
        $other = User::factory()->doctor()->create();
        $device = Device::factory()->for(Patient::factory()->for($other, 'doctor'))->create();

        $this->actingAs($doctor)
            ->get(route('devices.edit', $device))
            ->assertForbidden();
    }

    public function test_admin_can_delete_device(): void
    {
        $admin = User::factory()->admin()->create();
        $device = Device::factory()->create();

        $this->actingAs($admin)
            ->delete(route('devices.destroy', $device))
            ->assertRedirect();

        $this->assertDatabaseMissing('devices', ['id' => $device->id]);
    }

    public function test_doctor_cannot_delete_device(): void
    {
        $doctor = User::factory()->doctor()->create();
        $device = Device::factory()->for(Patient::factory()->for($doctor, 'doctor'))->create();

        $this->actingAs($doctor)
            ->delete(route('devices.destroy', $device))
            ->assertForbidden();

        $this->assertDatabaseHas('devices', ['id' => $device->id]);
    }
}

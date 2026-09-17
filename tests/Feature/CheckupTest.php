<?php

namespace Tests\Feature;

use App\Models\Checkup;
use App\Models\Device;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckupTest extends TestCase
{
    use RefreshDatabase;

    private function makeDeviceFor(User $doctor, string $implantedAt = '2023-01-15'): Device
    {
        return Device::factory()
            ->for(Patient::factory()->for($doctor, 'doctor'))
            ->create(['implanted_at' => $implantedAt]);
    }

    private function checkupPayload(array $overrides = []): array
    {
        return array_replace([
            'checked_at' => '2024-06-01',
            'battery_level' => 50,
            'notes' => 'Acompanhamento de rotina.',
        ], $overrides);
    }

    public function test_doctor_can_register_checkup_for_device_of_own_patient(): void
    {
        $doctor = User::factory()->doctor()->create();
        $device = $this->makeDeviceFor($doctor);

        $this->actingAs($doctor)
            ->post(route('devices.checkups.store', $device), $this->checkupPayload())
            ->assertRedirect();

        $this->assertDatabaseHas('checkups', [
            'device_id' => $device->id,
            'doctor_id' => $doctor->id,
            'battery_level' => 50,
        ]);
    }

    public function test_checkup_before_implant_date_is_rejected(): void
    {
        $doctor = User::factory()->doctor()->create();
        $device = $this->makeDeviceFor($doctor, '2024-01-15');

        $this->actingAs($doctor)
            ->post(route('devices.checkups.store', $device), $this->checkupPayload(['checked_at' => '2023-12-01']))
            ->assertSessionHasErrors('checked_at');
    }

    public function test_battery_level_out_of_range_is_rejected(): void
    {
        $doctor = User::factory()->doctor()->create();
        $device = $this->makeDeviceFor($doctor);

        $this->actingAs($doctor)
            ->post(route('devices.checkups.store', $device), $this->checkupPayload(['battery_level' => 150]))
            ->assertSessionHasErrors('battery_level');
    }

    public function test_doctor_can_access_register_checkup_form(): void
    {
        $doctor = User::factory()->doctor()->create();
        $device = $this->makeDeviceFor($doctor);

        $this->actingAs($doctor)
            ->get(route('devices.checkups.create', $device))
            ->assertOk();
    }

    public function test_doctor_can_edit_checkup_of_own_patient_device(): void
    {
        $doctor = User::factory()->doctor()->create();
        $device = $this->makeDeviceFor($doctor);
        $checkup = Checkup::factory()->for($device)->for($doctor, 'doctor')->create();

        $this->actingAs($doctor)
            ->put(route('devices.checkups.update', [$device, $checkup]), $this->checkupPayload(['battery_level' => 70]))
            ->assertRedirect();

        $this->assertDatabaseHas('checkups', [
            'id' => $checkup->id,
            'battery_level' => 70,
        ]);
    }

    public function test_doctor_can_access_edit_checkup_form(): void
    {
        $doctor = User::factory()->doctor()->create();
        $device = $this->makeDeviceFor($doctor);
        $checkup = Checkup::factory()->for($device)->for($doctor, 'doctor')->create();

        $this->actingAs($doctor)
            ->get(route('devices.checkups.edit', [$device, $checkup]))
            ->assertOk();
    }

    public function test_doctor_cannot_edit_checkup_of_other_doctors_patient(): void
    {
        $doctor = User::factory()->doctor()->create();
        $other = User::factory()->doctor()->create();
        $device = $this->makeDeviceFor($other);
        $checkup = Checkup::factory()->for($device)->for($other, 'doctor')->create();

        $this->actingAs($doctor)
            ->get(route('devices.checkups.edit', [$device, $checkup]))
            ->assertForbidden();
    }

    public function test_doctor_can_view_own_device_checkup_history(): void
    {
        $doctor = User::factory()->doctor()->create();
        $device = $this->makeDeviceFor($doctor);
        Checkup::factory()->count(2)->for($device)->for($doctor, 'doctor')->create(['battery_level' => 40]);

        $this->actingAs($doctor)
            ->get(route('devices.checkups.index', $device))
            ->assertOk()
            ->assertSee('40');
    }

    public function test_admin_can_delete_checkup(): void
    {
        $admin = User::factory()->admin()->create();
        $device = Device::factory()->create();
        $checkup = Checkup::factory()->for($device)->create();

        $this->actingAs($admin)
            ->delete(route('devices.checkups.destroy', [$device, $checkup]))
            ->assertRedirect();

        $this->assertDatabaseMissing('checkups', ['id' => $checkup->id]);
    }
}

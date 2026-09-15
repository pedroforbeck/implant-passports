<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Checkup;
use App\Models\Device;
use App\Models\Manufacturer;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = User::query()->withRole(Role::Doctor)->get();
        $manufacturers = Manufacturer::all();

        $account = User::query()->where('email', 'paciente@passaporte.test')->firstOrFail();

        $demo = Patient::factory()
            ->for($doctors->first(), 'doctor')
            ->for($account, 'user')
            ->create(['name' => $account->name]);

        $this->createDevicesFor($demo, $manufacturers, 1);

        Patient::factory(12)
            ->recycle($doctors)
            ->create()
            ->each(fn (Patient $patient) => $this->createDevicesFor($patient, $manufacturers, fake()->numberBetween(1, 2)));
    }

    private function createDevicesFor(Patient $patient, Collection $manufacturers, int $count): void
    {
        Device::factory($count)
            ->for($patient)
            ->recycle($manufacturers)
            ->has(Checkup::factory(3)->state(['doctor_id' => $patient->doctor_id]))
            ->create();
    }
}

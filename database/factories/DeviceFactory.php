<?php

namespace Database\Factories;

use App\Enums\DeviceStatus;
use App\Enums\DeviceType;
use App\Models\Manufacturer;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Device>
 */
class DeviceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'manufacturer_id' => Manufacturer::factory(),
            'type' => fake()->randomElement(DeviceType::cases()),
            'model' => strtoupper(fake()->bothify('??-###')),
            'serial_number' => strtoupper(fake()->unique()->bothify('SN########??')),
            'implanted_at' => fake()->dateTimeBetween('-10 years', '-1 year'),
            'hospital' => 'Hospital '.fake()->lastName(),
            'mri_conditional' => fake()->boolean(60),
            'status' => DeviceStatus::Active,
            'notes' => null,
        ];
    }

    public function explanted(): static
    {
        return $this->state(fn (array $attributes) => ['status' => DeviceStatus::Explanted]);
    }
}

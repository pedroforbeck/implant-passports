<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Checkup>
 */
class CheckupFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'device_id' => Device::factory(),
            'doctor_id' => User::factory()->doctor(),
            'checked_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'battery_level' => fake()->numberBetween(5, 100),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}

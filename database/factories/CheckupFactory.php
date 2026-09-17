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

    public function configure(): static
    {
        return $this->afterMaking(function (\App\Models\Checkup $checkup) {
            $implantedAt = $checkup->device?->implanted_at
                ?? ($checkup->device_id ? Device::whereKey($checkup->device_id)->value('implanted_at') : null);

            if ($implantedAt !== null && $checkup->checked_at !== null && $checkup->checked_at->lt($implantedAt)) {
                $checkup->checked_at = fake()->dateTimeBetween($implantedAt, 'now');
            }
        });
    }
}

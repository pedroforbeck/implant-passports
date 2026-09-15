<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'doctor_id' => User::factory()->doctor(),
            'name' => fake()->name(),
            'cpf' => fake()->unique()->numerify('###.###.###-##'),
            'birth_date' => fake()->dateTimeBetween('-90 years', '-18 years'),
            'blood_type' => fake()->randomElement(Patient::BLOOD_TYPES),
            'phone' => fake()->numerify('(##) 9####-####'),
            'emergency_contact_name' => fake()->name(),
            'emergency_contact_phone' => fake()->numerify('(##) 9####-####'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Contas de demonstração. Senha de todas: password
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Administrador',
            'email' => 'admin@passaporte.test',
        ]);

        User::factory()->doctor()->create([
            'name' => 'Dra. Helena Duarte',
            'email' => 'medica@passaporte.test',
        ]);

        User::factory()->doctor()->create([
            'name' => 'Dr. Rafael Moreira',
            'email' => 'medico@passaporte.test',
        ]);

        User::factory()->patient()->create([
            'name' => 'Paciente Demonstração',
            'email' => 'paciente@passaporte.test',
        ]);
    }
}

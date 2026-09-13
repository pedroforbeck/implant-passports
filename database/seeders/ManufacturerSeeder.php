<?php

namespace Database\Seeders;

use App\Models\Manufacturer;
use Illuminate\Database\Seeder;

class ManufacturerSeeder extends Seeder
{
    public function run(): void
    {
        $manufacturers = [
            ['name' => 'Medtronic', 'country' => 'Estados Unidos'],
            ['name' => 'Abbott', 'country' => 'Estados Unidos'],
            ['name' => 'Boston Scientific', 'country' => 'Estados Unidos'],
            ['name' => 'Biotronik', 'country' => 'Alemanha'],
            ['name' => 'MicroPort CRM', 'country' => 'França'],
        ];

        foreach ($manufacturers as $manufacturer) {
            Manufacturer::create($manufacturer);
        }
    }
}

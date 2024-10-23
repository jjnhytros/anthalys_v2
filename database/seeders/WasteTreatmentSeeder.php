<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recycling\WasteTreatment;

class WasteTreatmentSeeder extends Seeder
{
    public function run()
    {
        $treatments = [
            ['waste_type' => 'Organico', 'treatment_type' => 'Compostaggio', 'output_quantity' => 0.8, 'output_resource' => 'Compost'],
            ['waste_type' => 'Plastica', 'treatment_type' => 'Riciclo', 'output_quantity' => 0.6, 'output_resource' => 'Materiali Riciclati'],
            ['waste_type' => 'Vetro', 'treatment_type' => 'Riciclo', 'output_quantity' => 0.9, 'output_resource' => 'Vetro Riciclato'],
            ['waste_type' => 'Metalli', 'treatment_type' => 'Riciclo', 'output_quantity' => 0.7, 'output_resource' => 'Metalli Riciclati'],
        ];

        foreach ($treatments as $treatment) {
            WasteTreatment::create($treatment);
        }
    }
}

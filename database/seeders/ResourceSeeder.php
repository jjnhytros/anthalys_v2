<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Resource;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ResourceSeeder extends Seeder
{
    public function run()
    {
        $resources = [
            ['name' => 'Energia', 'unit' => 'kWh'],
            ['name' => 'Acqua', 'unit' => 'Litri'],
            ['name' => 'Cibo', 'unit' => 'Tonnes'],
        ];

        // Distribuiamo risorse per ogni distretto
        District::all()->each(function ($district) use ($resources) {
            $resource_data = [];

            foreach ($resources as $resource) {
                // Prepara i dati per l'inserimento
                $resource_data[] = array_merge($resource, [
                    'quantity' => rand(10000, 50000),
                    'produced' => rand(1000, 10000),
                    'consumed' => rand(1000, 10000),
                    'district_id' => $district->id,
                ]);
            }

            // Inseriamo tutte le risorse del distretto in un'unica operazione
            Resource::insert($resource_data);
        });
    }
}
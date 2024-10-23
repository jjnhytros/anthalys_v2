<?php

namespace Database\Seeders;

use App\Models\City\District;
use Illuminate\Database\Seeder;
use App\Models\Agricolture\AgriculturalResource;

class AgriculturalResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $resources = [
            ['name' => 'Grano', 'daily_production' => rand(500, 1000), 'daily_consumption' => rand(300, 800), 'water_consumption' => rand(100, 500), 'energy_consumption' => rand(50, 200)],
            ['name' => 'Ortaggi', 'daily_production' => rand(200, 600), 'daily_consumption' => rand(100, 400), 'water_consumption' => rand(50, 200), 'energy_consumption' => rand(30, 100)],
            ['name' => 'Frutta', 'daily_production' => rand(300, 700), 'daily_consumption' => rand(150, 500), 'water_consumption' => rand(80, 300), 'energy_consumption' => rand(40, 150)],
            ['name' => 'Cibo', 'unit' => 'kg', 'daily_production' => 1000, 'daily_consumption' => 900, 'water_consumption' => 0, 'energy_consumption' => 0],
            ['name' => 'Compost', 'unit' => 'kg', 'daily_production' => 100, 'daily_consumption' => 0, 'water_consumption' => 0, 'energy_consumption' => 0],

        ];

        // Per ogni distretto, aggiungiamo le risorse agricole
        District::all()->each(function ($district) use ($resources) {
            foreach ($resources as $resource) {
                AgriculturalResource::create([
                    'name' => $resource['name'],
                    'quantity' => rand(10000, 20000), // Quantità iniziale
                    'daily_production' => $resource['daily_production'],
                    'daily_consumption' => $resource['daily_consumption'],
                    'water_consumption' => $resource['water_consumption'],
                    'energy_consumption' => $resource['energy_consumption'],
                    'district_id' => $district->id,
                ]);
            }
        });
    }
}

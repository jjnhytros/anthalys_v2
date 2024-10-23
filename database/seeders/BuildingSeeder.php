<?php

namespace Database\Seeders;

use App\Models\City\Building;
use App\Models\City\District;
use Illuminate\Database\Seeder;

class BuildingSeeder extends Seeder
{
    public function run()
    {
        $types = ['Residenziale', 'Commerciale', 'Industriale'];

        // Per ogni distretto, creiamo un numero casuale di edifici
        District::all()->each(function ($district) use ($types) {
            $numBuildings = rand(10, 50); // Numero casuale di edifici
            $buildings_data = [];

            for ($i = 0; $i < $numBuildings; $i++) {
                $type = $types[array_rand($types)];
                $buildings_data[] = [
                    'name' => 'Edificio ' . ($i + 1),
                    'type' => $type,
                    'floors' => rand(1, 20),
                    'height' => rand(3, 50), // Altezza in metri
                    'district_id' => $district->id,
                    // Consumo delle risorse in base al tipo di edificio
                    'energy_consumption' => $type === 'Industriale' ? rand(1000, 5000) : rand(500, 2000),
                    'water_consumption' => rand(100, 1000), // Consumo idrico casuale
                    'food_consumption' => $type === 'Residenziale' ? rand(50, 500) : 0, // Solo i residenziali consumano cibo
                ];
            }

            // Inseriamo tutti gli edifici del distretto in un'unica operazione
            Building::insert($buildings_data);
        });
    }
}

<?php

namespace Database\Seeders;

use App\Models\City\City;
use App\Models\City\District;
use Illuminate\Database\Seeder;
use App\Models\Recycling\DistrictRecyclingGoal;

class CitySeeder extends Seeder
{
    public function run()
    {
        // Creiamo la città di Anthalys con una popolazione approssimativa di 2.600.000 abitanti
        $city = City::create([
            'name' => 'Anthalys',
            'latitude' => 0.0000,
            'longitude' => 0.0000,
            'population' => 2600000,  // Popolazione complessiva di Anthalys
            'climate' => 'Temperato'
        ]);

        // Distribuiamo la popolazione tra 12 distretti
        $total_population = 2600000;
        $remaining_population = $total_population;
        $districts_data = [
            ['name' => 'Centro Storico', 'area' => 15.5, 'description' => 'Il cuore antico della città.', 'type' => 'Residenziale'],
            ['name' => 'Quartiere Commerciale', 'area' => 12.3, 'description' => 'Centro economico con molti negozi.', 'type' => 'Commerciale'],
            ['name' => 'Zona Industriale', 'area' => 20.0, 'description' => 'Area dedicata alle industrie.', 'type' => 'Industriale'],
            // Aggiungi gli altri distretti...
        ];

        foreach ($districts_data as $index => $districtData) {
            if ($index == count($districts_data) - 1) {
                $population = $remaining_population; // L'ultimo distretto prende il resto della popolazione
            } else {
                $population = rand(150000, 300000); // Popolazione casuale per i distretti
                $remaining_population -= $population;
            }

            // Inserisci i distretti uno alla volta e ottieni il modello di ritorno
            $district = District::create(array_merge($districtData, [
                'population' => $population,
                'city_id' => $city->id,
            ]));

            // Aggiungi obiettivi di riciclo per il distretto
            $this->createRecyclingGoalsForDistrict($district);
        }
    }

    protected function createRecyclingGoalsForDistrict(District $district)
    {
        $resources = ['Plastica', 'Carta', 'Vetro', 'Alluminio'];

        foreach ($resources as $resource) {
            DistrictRecyclingGoal::create([
                'district_id' => $district->id,
                'resource_type' => $resource,
                'target_quantity' => rand(500, 2000), // Obiettivo casuale per il distretto
                'current_quantity' => 0
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\District;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
            ['name' => 'Centro Storico', 'area' => 15.5, 'description' => 'Il cuore antico della città.'],
            ['name' => 'Quartiere Commerciale', 'area' => 12.3, 'description' => 'Centro economico con molti negozi.'],
            ['name' => 'Zona Industriale', 'area' => 20.0, 'description' => 'Area dedicata alle industrie.'],
            ['name' => 'Borgo Nord', 'area' => 18.2, 'description' => 'Quartiere residenziale a nord della città.'],
            ['name' => 'Borgo Sud', 'area' => 17.0, 'description' => 'Zona tranquilla a sud.'],
            ['name' => 'Lungolago', 'area' => 25.5, 'description' => 'Area pittoresca vicino al lago.'],
            ['name' => 'Quartiere Universitario', 'area' => 10.5, 'description' => 'Sede delle principali università.'],
            ['name' => 'Zona Est', 'area' => 22.8, 'description' => 'Zona residenziale in espansione.'],
            ['name' => 'Zona Ovest', 'area' => 21.3, 'description' => 'Zona residenziale con molti parchi.'],
            ['name' => 'Porto', 'area' => 15.0, 'description' => 'Centro logistico per le attività marittime.'],
            ['name' => 'Quartiere delle Arti', 'area' => 8.5, 'description' => 'Il centro culturale della città.'],
            ['name' => 'Periferia', 'area' => 30.0, 'description' => 'Zona di nuova urbanizzazione.'],
        ];

        // Creiamo un array per i distretti con i loro dati e le popolazioni generate
        $districts_to_insert = [];
        foreach ($districts_data as $index => $district) {
            if ($index == count($districts_data) - 1) {
                $population = $remaining_population; // L'ultimo distretto prende il resto della popolazione
            } else {
                $population = rand(150000, 300000); // Popolazione casuale per i distretti
                $remaining_population -= $population;
            }

            $districts_to_insert[] = array_merge($district, [
                'population' => $population,
                'city_id' => $city->id,
            ]);
        }

        // Inseriamo tutti i distretti in un'unica operazione
        District::insert($districts_to_insert);
    }
}

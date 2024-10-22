<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Infrastructure;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InfrastructureSeeder extends Seeder
{
    public function run()
    {
        $infrastructures = [
            [
                'name' => 'Strada Principale',
                'type' => 'Strada',
                'length' => rand(5, 15),
                'capacity' => 1000,
                'efficiency' => 0.95,
                'co2_emissions' => rand(10, 50), // Emissioni moderate
                'energy_consumption' => rand(100, 500), // Consumo energetico medio
                'water_consumption' => rand(50, 200), // Consumo idrico basso
                'biodiversity_impact' => 0.1 // Impatto basso sulla biodiversità
            ],
            [
                'name' => 'Rete Elettrica',
                'type' => 'Rete Elettrica',
                'length' => rand(10, 50),
                'capacity' => 50000,
                'efficiency' => 0.85,
                'co2_emissions' => rand(200, 500), // Emissioni alte
                'energy_consumption' => rand(10000, 50000), // Consumo energetico elevato
                'water_consumption' => rand(500, 1000), // Consumo idrico medio
                'biodiversity_impact' => 0.5 // Impatto medio sulla biodiversità
            ],
            [
                'name' => 'Ponte di Collegamento',
                'type' => 'Ponte',
                'length' => rand(1, 5),
                'capacity' => 300,
                'efficiency' => 0.90,
                'co2_emissions' => rand(5, 20), // Emissioni basse
                'energy_consumption' => rand(50, 200), // Consumo energetico basso
                'water_consumption' => rand(10, 50), // Consumo idrico molto basso
                'biodiversity_impact' => 0.2 // Impatto basso sulla biodiversità
            ],
            [
                'name' => 'Acquedotto',
                'type' => 'Rete Idrica',
                'length' => rand(10, 30),
                'capacity' => 20000,
                'efficiency' => 0.92,
                'co2_emissions' => rand(50, 150), // Emissioni moderate
                'energy_consumption' => rand(2000, 5000), // Consumo energetico medio
                'water_consumption' => rand(1000, 3000), // Consumo idrico elevato
                'biodiversity_impact' => 0.3 // Impatto moderato sulla biodiversità
            ],
            [
                'name' => 'Rete Fognaria',
                'type' => 'Rete Fognaria',
                'length' => rand(10, 30),
                'capacity' => 15000,
                'efficiency' => 0.88,
                'co2_emissions' => rand(30, 100), // Emissioni moderate
                'energy_consumption' => rand(500, 2000), // Consumo energetico medio
                'water_consumption' => rand(500, 2000), // Consumo idrico moderato
                'biodiversity_impact' => 0.4 // Impatto medio sulla biodiversità
            ],
        ];

        // Distribuiamo le infrastrutture per ogni distretto
        District::all()->each(function ($district) use ($infrastructures) {
            $infrastructure_data = [];

            foreach ($infrastructures as $infrastructure) {
                // Prepara i dati per l'inserimento
                $infrastructure_data[] = array_merge($infrastructure, [
                    'district_id' => $district->id,
                ]);
            }

            // Inseriamo tutte le infrastrutture del distretto in un'unica operazione
            Infrastructure::insert($infrastructure_data);
        });
    }
}

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
            ['name' => 'Strada Principale', 'type' => 'Strada', 'length' => rand(5, 15), 'capacity' => 1000, 'efficiency' => 0.95],
            ['name' => 'Rete Elettrica', 'type' => 'Rete Elettrica', 'length' => rand(10, 50), 'capacity' => 50000, 'efficiency' => 0.85],
            ['name' => 'Ponte di Collegamento', 'type' => 'Ponte', 'length' => rand(1, 5), 'capacity' => 300, 'efficiency' => 0.90],
            ['name' => 'Acquedotto', 'type' => 'Rete Idrica', 'length' => rand(10, 30), 'capacity' => 20000, 'efficiency' => 0.92],
            ['name' => 'Rete Fognaria', 'type' => 'Rete Fognaria', 'length' => rand(10, 30), 'capacity' => 15000, 'efficiency' => 0.88],
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

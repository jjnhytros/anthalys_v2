<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\User;
use App\Models\Citizen;
use App\Models\Building;
use Illuminate\Database\Seeder;
use App\Models\AutoWasteDisposer;

class CitizenSeeder extends Seeder
{
    public function run()
    {
        // Creiamo l'utente government se non esiste
        $government = User::firstOrCreate(
            ['name' => 'government'],
            [
                'email' => 'government@example.com',
                'password' => bcrypt('password'), // Usa una password sicura
                'cash' => 100000.00, // Imposta il bilancio iniziale a zero
            ]
        );

        // Recuperiamo tutte le città
        $cities = City::all();

        foreach ($cities as $city) {
            // Recuperiamo edifici residenziali, commerciali e industriali associati alla città
            $residentialBuildings = Building::where('city_id', $city->id)->where('type', 'Residenziale')->get();
            $commercialBuildings = Building::where('city_id', $city->id)->where('type', 'Commerciale')->get();
            $industrialBuildings = Building::where('city_id', $city->id)->where('type', 'Industriale')->get();

            // Creiamo cittadini per ogni edificio residenziale
            foreach ($residentialBuildings as $resBuilding) {
                // Creiamo cittadini che vivono negli edifici residenziali
                $citizen = Citizen::create([
                    'name' => 'Cittadino ' . rand(1, 1000),
                    'is_employed' => (bool)rand(0, 1), // 50% dei cittadini sono impiegati
                    'income' => rand(10000, 50000), // Reddito casuale
                    'residential_building_id' => $resBuilding->id,
                    'cash' => rand(0, 1000),
                    'city_id' => $city->id,
                ]);

                // Se il cittadino è impiegato, assegnalo a un edificio commerciale o industriale
                if ($citizen->is_employed) {
                    if (rand(0, 1)) {
                        // Lavora in un edificio commerciale
                        $citizen->work_building_id = $commercialBuildings->random()->id;
                    } else {
                        // Lavora in un edificio industriale
                        $citizen->work_building_id = $industrialBuildings->random()->id;
                    }
                    $citizen->save(); // Salviamo l'aggiornamento del work_building_id
                }
            }
        }
        $citizens = Citizen::all();

        foreach ($citizens as $citizen) {
            if (rand(0, 1)) {
                // Assegniamo casualmente uno smaltitore automatico alle famiglie
                AutoWasteDisposer::create([
                    'type' => 'Compostatore',
                    'efficiency' => 85.00, // Riduzione rifiuti organici dell'85%
                    'citizen_id' => $citizen->id,
                ]);
            }
        }
    }
}

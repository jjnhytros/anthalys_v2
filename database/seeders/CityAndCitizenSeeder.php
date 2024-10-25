<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\City\City;
use App\Models\City\Citizen;
use App\Models\City\Building;
use App\Models\City\District;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\Recycling\AutoWasteDisposer;
use App\Models\Recycling\DistrictRecyclingGoal;

class CityAndCitizenSeeder extends Seeder
{
    public function run()
    {
        // Prima, creiamo la città
        $city = City::create([
            'name' => 'Anthalys',
            'latitude' => 0.0000,
            'longitude' => 0.0000,
            'population' => 2600000,
            'climate' => 'Temperato'
        ]);
        $this->createCitizens($city);
        $first_manager = Citizen::find(4);


        // Creazione di altri 24 utenti e cittadini casuali
        for ($i = 1; $i <= 24; $i++) {
            $user = User::create([
                'name' => 'user' . $i,
                'email' => 'user' . $i . '@example.com',
                'password' => bcrypt('password'),
                'cash' => rand(100, 1000),
            ]);

            $citizen = Citizen::create([
                'name' => 'Citizen ' . $i,
                'user_id' => $user->id,
                'is_employed' => (bool)rand(0, 1),
                'income' => rand(10000, 50000),
                'cash' => rand(0, 1000),
                'city_id' => City::inRandomOrder()->first()->id,
            ]);

            // Se il cittadino è impiegato, assegniamo un edificio commerciale o industriale
            if ($citizen->is_employed) {
                $work_building_type = rand(0, 1) ? 'Commerciale' : 'Industriale';

                // Recuperiamo l'edificio commerciale o industriale
                $work_building = Building::where('city_id', $citizen->city_id)
                    ->where('type', $work_building_type)
                    ->inRandomOrder()
                    ->first();

                // Verifichiamo se esiste un edificio disponibile
                if ($work_building) {
                    $citizen->work_building_id = $work_building->id;
                    $citizen->save();
                }
            }
        }
        $managers = Citizen::where('id', '>', 4)->inRandomOrder()->limit(11)->get(); // 11 per i restanti distretti
        if ($managers->count() < 11) {
            throw new \Exception("Non ci sono abbastanza cittadini per assegnare i manager ai distretti.");
        }
        $total_area = $this->createDistricts($city, $first_manager, $managers);

        // Aggiungi smaltitori automatici per alcuni cittadini casuali
        $citizens = Citizen::all();
        foreach ($citizens as $citizen) {
            if (rand(0, 1)) {
                AutoWasteDisposer::create([
                    'type' => 'Compostatore',
                    'efficiency' => 85.00,
                    'citizen_id' => $citizen->id,
                ]);
            }
        }

        // Crea il Warehouse come distretto
        $this->createMegaWarehouse($city, $total_area * .75);
    }

    protected function createCitizens(City $city)
    {

        // Crea l'utente Admin (NPC)
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'cash' => 0,
        ]);
        $admin->assignRole('admin');

        Citizen::create([
            'name' => 'Admin NPC',
            'user_id' => $admin->id,
            'is_employed' => true,
            'income' => 50000,
            'cash' => 5000,
            'city_id' => City::inRandomOrder()->first()->id,
        ]);

        // Crea l'utente Government (NPC)
        $government = User::create([
            'name' => 'government',
            'email' => 'government@example.com',
            'password' => bcrypt('password'),
            'cash' => 100000.00,
        ]);
        $government->assignRole('government');

        Citizen::create([
            'name' => 'Government NPC',
            'user_id' => $government->id,
            'is_employed' => true,
            'income' => 0,
            'cash' => 100000.00,
            'city_id' => City::inRandomOrder()->first()->id,
        ]);

        // Crea l'utente Bank (NPC)
        $bank = User::create([
            'name' => 'bank',
            'email' => 'bank@example.com',
            'password' => bcrypt('password'),
            'cash' => 500000.00,
        ]);
        $bank->assignRole('bank');

        Citizen::create([
            'name' => 'Bank NPC',
            'user_id' => $bank->id,
            'is_employed' => true,
            'income' => 0,
            'cash' => 500000.00,
            'city_id' => City::inRandomOrder()->first()->id,
        ]);

        // Crea l'utente jjnhytros (PG)
        $jjnhytros = User::create([
            'name' => 'jjnhytros',
            'email' => 'jjnhytros@example.com',
            'password' => bcrypt('password'),
            'cash' => 1000,
        ]);
        $roles = Role::all();
        foreach ($roles as $role) {
            $jjnhytros->assignRole($role);
        }

        Citizen::create([
            'name' => 'J.J.Nhytros',
            'user_id' => $jjnhytros->id,
            'is_employed' => true,
            'income' => 50000,
            'cash' => 1000,
            'city_id' => City::inRandomOrder()->first()->id,
        ]);
    }

    protected function createDistricts(City $city, $first_manager, $managers)
    {
        $total_population = 2600000;
        $remaining_population = $total_population;
        $total_area = 0;

        // Seleziona i manager dal citizen 5 in poi per gli altri distretti
        $managers = Citizen::where('id', '>', 4)->inRandomOrder()->limit(11)->get(); // 11 per i restanti distretti

        $districts_data = [
            ['name' => 'Centro Storico', 'area' => 15.5, 'description' => 'Il cuore antico della città.', 'type' => 'Residenziale'],
            ['name' => 'Quartiere Commerciale', 'area' => 12.3, 'description' => 'Centro economico con molti negozi.', 'type' => 'Commerciale'],
            ['name' => 'Zona Industriale', 'area' => 20.0, 'description' => 'Area dedicata alle industrie.', 'type' => 'Industriale'],
            ['name' => 'Quartiere Verde', 'area' => 25.1, 'description' => 'Area verde con numerosi parchi e giardini.', 'type' => 'Residenziale'],
            ['name' => 'Porto Antico', 'area' => 18.7, 'description' => 'Area costiera con attività portuali e cantieri navali.', 'type' => 'Industriale'],
            ['name' => 'Città Bassa', 'area' => 14.9, 'description' => 'Quartiere residenziale di media densità abitativa.', 'type' => 'Residenziale'],
            ['name' => 'Quartiere degli Artisti', 'area' => 10.4, 'description' => 'Zona creativa con atelier e gallerie d\'arte.', 'type' => 'Residenziale'],
            ['name' => 'Nuova Cittadella', 'area' => 17.8, 'description' => 'Area moderna con grattacieli e uffici aziendali.', 'type' => 'Commerciale'],
            ['name' => 'Cintura Agricola', 'area' => 30.2, 'description' => 'Zona rurale con fattorie e campi agricoli.', 'type' => 'Agricolo'],
            ['name' => 'Quartiere Universitario', 'area' => 9.3, 'description' => 'Area accademica con università e centri di ricerca.', 'type' => 'Residenziale'],
            ['name' => 'Zona Sud', 'area' => 16.6, 'description' => 'Area residenziale con sviluppo recente.', 'type' => 'Residenziale'],
            ['name' => 'Quartiere Nord', 'area' => 21.4, 'description' => 'Area residenziale periferica.', 'type' => 'Residenziale'],
        ];

        foreach ($districts_data as $index => $districtData) {
            if ($index == count($districts_data) - 1) {
                $population = $remaining_population; // L'ultimo distretto prende il resto della popolazione
            } else {
                $population = rand(150000, 300000); // Popolazione casuale per i distretti
                $remaining_population -= $population;
            }
            // Assegna il manager specifico per il primo distretto
            if ($index == 0) {
                $manager = $first_manager; // Citizen con ID 4
            } else {
                $manager = $managers->pop(); // Manager dal citizen 5 in avanti
            }
            // Inserisci i distretti uno alla volta e ottieni il modello di ritorno
            $district = District::create(array_merge($districtData, [
                'population' => $population,
                'city_id' => $city->id,
                'manager_id' => $manager->id,
            ]));

            $total_area += $districtData['area'];

            // Aggiungi obiettivi di riciclo per il distretto
            $this->createRecyclingGoalsForDistrict($district);
        }
        return $total_area;
    }

    protected function createMegaWarehouse(City $city, $area)
    {
        District::create([
            'name' => 'MegaWarehouse',
            'type' => 'Commerciale',
            'area' => $area, // Dimensione ipotetica del MegaWarehouse
            'description' => 'Distretto autonomo e sotterraneo, specializzato in import/export online e gestito principalmente da AI.',
            'city_id' => $city->id,
            'population' => 0, // Popolazione del distretto (opzionale se non necessaria)
            'manager_id' => null, // Gestito da AI, quindi senza manager umano
            'tax_rate' => 26.0, // Tassa fissa
            'auto_sufficient' => true, // Autoapprovvigionamento
        ]);
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

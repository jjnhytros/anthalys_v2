<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\City\City;
use App\Models\City\Citizen;
use App\Models\City\Building;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use App\Models\Recycling\AutoWasteDisposer;

class CitizenSeeder extends Seeder
{
    public function run()
    {
        // Crea l'utente Admin (NPC)
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'cash' => 0,
        ]);

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

        Citizen::create([
            'name' => 'JJNHYTROS',
            'user_id' => $jjnhytros->id,
            'is_employed' => true,
            'income' => 50000,
            'cash' => 1000,
            'city_id' => City::inRandomOrder()->first()->id,
        ]);
        Auth::login($jjnhytros);

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
    }
}

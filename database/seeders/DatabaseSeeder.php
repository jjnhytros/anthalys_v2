<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::find(4);
        if ($user && Auth::id() === $user->id) {
            Auth::logout();
            Session::flush();
        }

        $this->call([
            CitySeeder::class,
            BuildingSeeder::class,
            ResourceSeeder::class,
            InfrastructureSeeder::class,
            EventSeeder::class,
            CitizenSeeder::class,
            WasteTypeSeeder::class,
            WasteTreatmentSeeder::class,
            SeasonSeeder::class,
            AgriculturalTechniqueSeeder::class,
            AgriculturalResourceSeeder::class,
            AlcoholicSeeder::class,
            PolicySeeder::class,
            WorkPolicySeeder::class,
        ]);
    }
}

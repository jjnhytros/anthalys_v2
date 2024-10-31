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
        config(['app.disable_auth_during_seeding' => true]);
        $this->call([
            RoleSeeder::class,
            CityAndCitizenSeeder::class,
            // ExperienceLevelSeeder::class,
            // BuildingSeeder::class,
            // ResourceSeeder::class,
            // InfrastructureSeeder::class,
            // EventSeeder::class,
            // WasteTypeSeeder::class,
            // WasteTreatmentSeeder::class,
            // SeasonSeeder::class,
            // AgriculturalTechniqueSeeder::class,
            // AgriculturalResourceSeeder::class,
            // AlcoholicSeeder::class,
            // PolicySeeder::class,
            // WorkPolicySeeder::class,
        ]);
        config(['app.disable_auth_during_seeding' => false]);
    }
}

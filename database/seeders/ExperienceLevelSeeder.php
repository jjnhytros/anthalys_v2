<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City\ExperienceLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExperienceLevelSeeder extends Seeder
{
    public function run()
    {
        $experienceRequired = 100;
        for ($level = 1; $level <= 24; $level++) {
            ExperienceLevel::create([
                'level' => $level,
                'experience_required' => $experienceRequired,
            ]);
            $experienceRequired = ceil($experienceRequired * 1.5); // Incremento esponenziale
        }
    }
}

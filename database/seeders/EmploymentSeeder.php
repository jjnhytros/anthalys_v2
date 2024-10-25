<?php

namespace Database\Seeders;

use App\Models\City\Skill;
use App\Models\City\Occupation;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmploymentSeeder extends Seeder
{
    public function run()
    {
        // Occupazioni iniziali
        $occupations = [
            [
                'title' => 'Agricoltore',
                'description' => 'Responsabile della coltivazione e raccolta di prodotti agricoli.',
                'salary' => 15000,
                'stress_level' => 3,
            ],
            [
                'title' => 'Commerciante',
                'description' => 'Gestisce la vendita di beni al mercato locale.',
                'salary' => 22000,
                'stress_level' => 5,
            ],
            [
                'title' => 'Ingegnere Ambientale',
                'description' => 'Gestisce progetti legati alla sostenibilità ambientale.',
                'salary' => 35000,
                'stress_level' => 7,
            ],
        ];

        foreach ($occupations as $occupation) {
            Occupation::create($occupation);
        }

        // Abilità iniziali
        $skills = [
            ['name' => 'Coltivazione', 'description' => 'Capacità di gestire coltivazioni e raccolti.'],
            ['name' => 'Commercio', 'description' => 'Esperienza nella gestione delle vendite e negoziazioni.'],
            ['name' => 'Sostenibilità', 'description' => 'Competenze per promuovere pratiche ecologiche.'],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }
    }
}

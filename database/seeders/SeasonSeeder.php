<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agricolture\Season;

class SeasonSeeder extends Seeder
{
    public function run()
    {
        Season::create([
            'name' => 'Luminara',
            'start_day' => 1,
            'end_day' => 72,
            'impact_factor' => 1.2 // Es: 20% di aumento della produzione agricola
        ]);

        Season::create([
            'name' => 'Marea Bianca',
            'start_day' => 73,
            'end_day' => 144,
            'impact_factor' => 1.0 // Produzione neutrale
        ]);

        Season::create([
            'name' => 'Crepuscolo Dorato',
            'start_day' => 145,
            'end_day' => 216,
            'impact_factor' => 0.9 // Produzione ridotta del 10%
        ]);

        Season::create([
            'name' => 'Ombra Fredda',
            'start_day' => 217,
            'end_day' => 288,
            'impact_factor' => 0.7 // Produzione ridotta del 30%
        ]);

        Season::create([
            'name' => 'Risveglio delle Maree',
            'start_day' => 289,
            'end_day' => 360,
            'impact_factor' => 1.1 // Produzione aumentata del 10%
        ]);

        Season::create([
            'name' => 'Fertilità Oscura',
            'start_day' => 361,
            'end_day' => 432,
            'impact_factor' => 0.85 // Produzione ridotta del 15%
        ]);
    }
}

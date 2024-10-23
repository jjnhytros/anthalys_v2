<?php

namespace Database\Seeders;

use App\Models\City\Policy;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PolicySeeder extends Seeder
{
    public function run()
    {
        // Politica fiscale di esempio
        Policy::create([
            'name' => 'Aliquota Fiscale',
            'type' => 'tax',
            'rate' => 20.00, // 20% di aliquota
            'description' => 'Aliquota fiscale standard applicata a tutti i cittadini attivi.',
            'active' => true,
        ]);

        // Politica di sussidio di esempio
        Policy::create([
            'name' => 'Sussidio Energetico',
            'type' => 'subsidy',
            'rate' => 15.00, // 15% di sussidio
            'description' => 'Riduzione del costo energetico per famiglie a basso reddito.',
            'active' => true,
        ]);
    }
}

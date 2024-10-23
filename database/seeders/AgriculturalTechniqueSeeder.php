<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agricolture\AgriculturalTechnique;

class AgriculturalTechniqueSeeder extends Seeder
{
    public function run()
    {
        AgriculturalTechnique::create([
            'name' => 'Agricoltura Biologica',
            'description' => 'Metodo che utilizza pratiche sostenibili e prive di pesticidi chimici.',
            'efficiency_boost' => 1.05, // Aumento di efficienza del 5%
            'sustainability_level' => 1.20, // Aumento di sostenibilità del 20%
        ]);

        AgriculturalTechnique::create([
            'name' => 'Permacultura',
            'description' => 'Sistema di progettazione agricola che imita gli ecosistemi naturali.',
            'efficiency_boost' => 1.10,
            'sustainability_level' => 1.25,
        ]);

        AgriculturalTechnique::create([
            'name' => 'Agricoltura Sinergica',
            'description' => 'Pratica di coltivazione che sfrutta le sinergie tra diverse piante e il terreno.',
            'efficiency_boost' => 1.08,
            'sustainability_level' => 1.15,
        ]);
    }
}

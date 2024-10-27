<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resource\Material;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MaterialSeeder extends Seeder
{
    public function run()
    {
        $materials = [
            [
                'name' => 'Acciaio',
                'composition' => 'Ferro, Carbonio',
                'durability' => 100,
                'density' => 7.85, // g/cm³
            ],
            [
                'name' => 'Calcestruzzo',
                'composition' => 'Cemento, Sabbia, Ghiaia, Acqua',
                'durability' => 80,
                'density' => 2.4, // g/cm³
            ],
            [
                'name' => 'Legno',
                'composition' => 'Cellulosa, Lignina',
                'durability' => 60,
                'density' => 0.6, // g/cm³
            ],
            [
                'name' => 'Alluminio',
                'composition' => 'Alluminio Puro',
                'durability' => 90,
                'density' => 2.7, // g/cm³
            ],
            [
                'name' => 'Vetro',
                'composition' => 'Silice, Sodio, Calcio',
                'durability' => 70,
                'density' => 2.5, // g/cm³
            ],
            [
                'name' => 'Rame',
                'composition' => 'Rame Puro',
                'durability' => 85,
                'density' => 8.96, // g/cm³
                'conductivity' => 385, // W/mK
                'thermal_resistance' => 0.02,
                'recyclability' => 100, // %
            ],
            [
                'name' => 'Plastica Riciclata',
                'composition' => 'Polimeri Riciclati',
                'durability' => 40,
                'density' => 0.95, // g/cm³
                'conductivity' => 0.25, // W/mK
                'thermal_resistance' => 0.4,
                'recyclability' => 50, // %
            ],
            [
                'name' => 'Metallo Anthaliano',
                'composition' => 'Metallo naturale',
                'durability' => 200,
                'density' => 0.72, // g/cm³
                'conductivity' => 0, // W/mK
                'thermal_resistance' => 25.0,
                'recyclability' => 100, // %
            ],
            [
                'name' => 'Titanio',
                'composition' => 'Titanio puro',
                'durability' => 95,
                'density' => 4.5, // g/cm³
                'conductivity' => 22, // W/mK
                'thermal_resistance' => 0.8,
                'recyclability' => 90, // %
            ],
            [
                'name' => 'Polietilene (PE)',
                'composition' => 'Polimero di etilene',
                'durability' => 40,
                'density' => 0.94, // g/cm³
                'conductivity' => 0.42, // W/mK
                'thermal_resistance' => 0.2,
                'recyclability' => 75, // %
            ],

        ];

        foreach ($materials as $material) {
            Material::create($material);
        }
    }
}

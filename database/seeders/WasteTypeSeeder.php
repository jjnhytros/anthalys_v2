<?php

namespace Database\Seeders;

use App\Models\WasteType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WasteTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WasteType::insert([
            ['name' => 'Organico', 'container_color' => 'Marrone', 'description' => 'Rifiuti alimentari, scarti di cibo, foglie e altri rifiuti biodegradabili.'],
            ['name' => 'Carta e Cartone', 'container_color' => 'Blu', 'description' => 'Carta, cartone, giornali, riviste, scatole di cartone.'],
            ['name' => 'Plastica', 'container_color' => 'Giallo', 'description' => 'Bottiglie di plastica, contenitori di plastica, sacchetti di plastica derivati da materiali naturali come bioplastiche.'],
            ['name' => 'Vetro', 'container_color' => 'Verde', 'description' => 'Bottiglie di vetro, barattoli di vetro, contenitori di vetro.'],
            ['name' => 'Metalli', 'container_color' => 'Grigio', 'description' => 'Lattine, barattoli di metallo, scarti metallici.'],
            ['name' => 'Rifiuti Elettronici', 'container_color' => 'Rosso', 'description' => 'Dispositivi elettronici, batterie, piccoli elettrodomestici.'],
            ['name' => 'Rifiuti Speciali', 'container_color' => 'Nero', 'description' => 'Materiali naturali speciali che richiedono trattamenti specifici (es. oli vegetali usati, sostanze naturali speciali).'],
        ]);
    }
}

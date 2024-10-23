<?php

namespace Database\Seeders;

use App\Models\Alcoholic;
use App\Models\Ingredient;
use App\Models\ProductionPhase;
use Illuminate\Database\Seeder;

class AlcoholicSeeder extends Seeder
{
    public function run()
    {
        // Creazione delle fasi di produzione
        $fermentation = ProductionPhase::create(['name' => 'Fermentazione', 'description' => 'Fermentazione del malto', 'duration' => 14]);
        $maturation = ProductionPhase::create(['name' => 'Maturazione', 'description' => 'Maturazione della bevanda', 'duration' => 30]);
        $distillation = ProductionPhase::create(['name' => 'Distillazione', 'description' => 'Distillazione degli ingredienti', 'duration' => 7]);

        // Creazione degli ingredienti
        $orzo = Ingredient::create(['name' => 'Orzo', 'type' => 'Cereale', 'quantity' => 1000, 'unit' => 'kg']);
        $luppolo = Ingredient::create(['name' => 'Luppolo', 'type' => 'Fiore', 'quantity' => 500, 'unit' => 'kg']);
        $lievito = Ingredient::create(['name' => 'Lievito', 'type' => 'Fungo', 'quantity' => 200, 'unit' => 'g']);
        $mais = Ingredient::create(['name' => 'Mais', 'type' => 'Cereale', 'quantity' => 1500, 'unit' => 'kg']);
        $anice = Ingredient::create(['name' => 'Anice Stellato', 'type' => 'Spezia', 'quantity' => 100, 'unit' => 'g']);
        $finocchio = Ingredient::create(['name' => 'Finocchio Selvatico', 'type' => 'Erba', 'quantity' => 50, 'unit' => 'g']);
        $liquirizia = Ingredient::create(['name' => 'Liquirizia', 'type' => 'Radice', 'quantity' => 30, 'unit' => 'g']);

        // Produzione del Whisky Locale
        $whisky = Alcoholic::create([
            'name' => 'Whisky Locale',
            'type' => 'Whisky',
            'batch_size' => 500, // Quantità prodotta in litri
            'malt_type' => 'Orzo Biologico',
            'yeast_type' => 'Lievito Selezionato',
            'water_source' => 'Fonte Naturale di Anthalys',
            'production_phase' => 'Distillazione',
            'fermentation_time' => 4, // giorni
            'maturation_time' => 1095, // 3 anni in giorni
            'ingredient' => 'Orzo',
            'process_stage' => 'Maturazione', // Campo process_stage obbligatorio
            'environmental_impact' => 2.5, // Impatto ambientale su scala di 10
        ]);
        $whisky->ingredients()->attach([
            $orzo->id => ['quantity_used' => 100],
            $lievito->id => ['quantity_used' => 10],
        ]);
        $whisky->productionPhases()->attach([$distillation->id, $maturation->id]);

        // Produzione del Bourbon Locale
        $bourbon = Alcoholic::create([
            'name' => 'Bourbon Locale',
            'type' => 'Bourbon',
            'batch_size' => 1000, // Quantità prodotta in litri
            'malt_type' => 'Mais Biologico, Segale, Orzo',
            'yeast_type' => 'Lievito Selezionato',
            'water_source' => 'Fonte Naturale di Anthalys',
            'production_phase' => 'Maturazione',
            'fermentation_time' => 7, // giorni
            'maturation_time' => 730, // 2 anni in giorni
            'ingredient' => 'Mais',
            'process_stage' => 'Maturazione in botte', // Campo process_stage obbligatorio
            'environmental_impact' => 3.0, // Impatto ambientale su scala di 10
        ]);
        $bourbon->ingredients()->attach([
            $mais->id => ['quantity_used' => 500],
            $orzo->id => ['quantity_used' => 50],
            $lievito->id => ['quantity_used' => 20],
        ]);
        $bourbon->productionPhases()->attach([$distillation->id, $maturation->id]);

        // Produzione della Sambuca Naturale
        $sambuca = Alcoholic::create([
            'name' => 'Sambuca Naturale',
            'type' => 'Sambuca',
            'batch_size' => 500, // Quantità prodotta in litri
            'yeast_type' => 'Lievito Selezionato',
            'water_source' => 'Fonte Naturale di Anthalys',
            'production_phase' => 'Distillazione',
            'fermentation_time' => 14, // giorni
            'maturation_time' => 30, // settimane in acciaio
            'ingredient' => 'Anice Stellato, Finocchio Selvatico, Liquirizia',
            'process_stage' => 'Distillazione a Vapore', // Campo process_stage obbligatorio
            'environmental_impact' => 2.5, // Impatto ambientale su scala di 10
        ]);
        $sambuca->ingredients()->attach([
            $anice->id => ['quantity_used' => 5],
            $finocchio->id => ['quantity_used' => 3],
            $liquirizia->id => ['quantity_used' => 2],
        ]);
        $sambuca->productionPhases()->attach([$distillation->id, $maturation->id]);

        // Produzione delle birre
        $beers = [
            [
                'name' => 'Birra Naturale Premium',
                'type' => 'Birra',
                'batch_size' => 1000, // Quantità prodotta per lotto
                'malt_type' => 'Malto d\'orzo biologico',
                'hop_type' => 'Luppolo locale',
                'yeast_type' => 'Lievito selezionato',
                'water_source' => 'Acqua di sorgente naturale',
                'production_phase' => 'Fermentazione',
                'fermentation_time' => 14, // Tempo in giorni
                'maturation_time' => 30, // Tempo in giorni
                'ingredient' => 'Orzo, Luppolo, Lievito',
                'process_stage' => 'Fermentazione', // Campo process_stage obbligatorio
                'environmental_impact' => 0.5, // Impatto ambientale (valore indicativo)
            ],
            [
                'name' => 'Birra Tradizionale Rossa',
                'type' => 'Birra',
                'batch_size' => 800,
                'malt_type' => 'Malto tostato',
                'hop_type' => 'Luppolo aromatico',
                'yeast_type' => 'Lievito artigianale',
                'water_source' => 'Acqua trattata naturalmente',
                'production_phase' => 'Maturazione',
                'fermentation_time' => 10,
                'maturation_time' => 45,
                'ingredient' => 'Malto, Luppolo, Lievito',
                'process_stage' => 'Maturazione', // Campo process_stage obbligatorio
                'environmental_impact' => 0.7,
            ],
            [
                'name' => 'Birra Leggera Bio',
                'type' => 'Birra',
                'batch_size' => 1200,
                'malt_type' => 'Malto chiaro',
                'hop_type' => 'Luppolo leggero',
                'yeast_type' => 'Lievito naturale',
                'water_source' => 'Acqua di falda',
                'production_phase' => 'Imbottigliamento',
                'fermentation_time' => 7,
                'maturation_time' => 20,
                'ingredient' => 'Malto, Luppolo, Lievito',
                'process_stage' => 'Imbottigliamento', // Campo process_stage obbligatorio
                'environmental_impact' => 0.4,
            ]
        ];

        // Creazione delle birre nel database
        foreach ($beers as $beerData) {
            $beer = Alcoholic::create($beerData);
            $beer->ingredients()->attach([
                $orzo->id => ['quantity_used' => 50],
                $luppolo->id => ['quantity_used' => 10],
                $lievito->id => ['quantity_used' => 5],
            ]);
            $beer->productionPhases()->attach([$fermentation->id, $maturation->id]);
        }
    }
}

<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\Agricolture\Farm;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Agricolture\ProductionReport;

class GenerateMonthlyProductionReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $farm;

    public function __construct(Farm $farm)
    {
        $this->farm = $farm;
    }

    public function handle()
    {
        // Calcolo delle rese complessive
        $total_crop_yield = $this->farm->crops->sum('yield');
        $total_animal_yield = $this->farm->animals->sum('yield');
        $vertical_farming_yield = $this->farm->greenhouses->where('type', 'Verticale')->sum('yield_multiplier');

        // Creazione del report mensile
        ProductionReport::create([
            'farm_id' => $this->farm->id,
            'total_crop_yield' => $total_crop_yield,
            'total_animal_yield' => $total_animal_yield,
            'vertical_farming_yield' => $vertical_farming_yield,
            'report_period' => now()->format('Y-m'), // Esempio di formato anno-mese
            'type' => 'monthly',
        ]);

        // Invia una notifica o salva il report da visualizzare
    }
}

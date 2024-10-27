<?php

namespace App\Services\Resources;

use App\Models\City\District;

class ResourcePredictionService
{
    public function predictDemand(District $district)
    {
        // Modello di predizione della domanda basato su regressione lineare
        $populationWeight = 0.003; // Peso per la popolazione
        $previousDemandWeight = 1.2; // Peso per la domanda precedente

        // Calcolo della domanda predetta
        $predictedDemand = ($district->population * $populationWeight) +
            ($district->previous_demand * $previousDemandWeight);

        return round($predictedDemand, 2); // Arrotondamento per due decimali
    }
}

<?php

namespace App\Services\Resources;

use App\Models\Resource\Resource;

class PatternAnalysisService
{
    public function analyzeConsumptionPatterns()
    {
        // Recupera tutti i dati storici delle risorse
        $resources = Resource::with('history')->get();

        // Identifica i distretti con pattern di deficit ripetuti
        $deficitDistricts = [];
        foreach ($resources as $resource) {
            $averageConsumption = $resource->history->avg('availability');
            $currentConsumption = $resource->availability;

            // Se il consumo attuale supera la media, segnala come deficit
            if ($currentConsumption < $averageConsumption * 0.72) {
                $deficitDistricts[$resource->id] = $resource->district->id;
            }
        }

        return $deficitDistricts;
    }
}

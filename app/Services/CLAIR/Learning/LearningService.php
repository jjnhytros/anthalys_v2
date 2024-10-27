<?php

namespace App\Services\CLAIR\Learning;

use App\Models\CLAIR;
use App\Models\Resource\Resource;

class LearningService
{
    protected $type = 'L'; // L per Learning

    public function predictResourceDemand(Resource $resource)
    {
        $averageDemand = $resource->history->avg('availability');
        $predictedDemand = $averageDemand * 1.05;

        CLAIR::logActivity($this->type, 'predictResourceDemand', [
            'resource_id' => $resource->id,
            'predicted_demand' => $predictedDemand,
        ], $resource->name, $resource->id, 'predicted', 'Previsione della domanda completata');

        return $predictedDemand;
    }

    public function predictBuildingImpact($building)
    {
        $predictedEnergy = $building->energy_consumption * 1.05;
        $predictedWater = $building->water_consumption * 1.05;

        CLAIR::logActivity($this->type, 'predictBuildingImpact', [
            'building_id' => $building->id,
            'predicted_energy' => $predictedEnergy,
            'predicted_water' => $predictedWater,
        ], null, $building->id, 'predicted', 'Previsione dell’impatto energetico e idrico per edificio completata');

        return [
            'predicted_energy' => $predictedEnergy,
            'predicted_water' => $predictedWater,
        ];
    }
}

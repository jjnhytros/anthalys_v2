<?php

namespace App\Services\CLAIR\Comprehension;

use App\Models\CLAIR;
use App\Models\Resource\Resource;

class ComprehensionService
{
    protected $type = 'C'; // C per Comprehension

    public function analyzeConsumptionPatterns()
    {
        $deficitDistricts = [];
        $resources = Resource::with('history')->get();

        foreach ($resources as $resource) {
            $averageAvailability = $resource->history->avg('availability');
            $currentAvailability = $resource->availability;

            if ($currentAvailability < $averageAvailability * 0.72) {
                $deficitDistricts[$resource->id] = $resource->district->id;
            }
        }

        CLAIR::logActivity($this->type, 'analyzeConsumptionPatterns', [
            'deficit_districts' => $deficitDistricts,
        ], null, null, 'completed', 'Analisi dei consumi completata con successo');

        return $deficitDistricts;
    }

    public function analyzeBuildingImpact($buildings)
    {
        $impact = [];
        foreach ($buildings as $building) {
            $impact[] = [
                'name' => $building->name,
                'energy' => $building->energy_consumption,
                'water' => $building->water_consumption,
                'food' => $building->food_consumption,
            ];
        }

        CLAIR::logActivity($this->type, 'analyzeBuildingImpact', [
            'building_impact' => $impact,
        ], null, null, 'completed', 'Analisi dell’impatto degli edifici completata con successo');

        return $impact;
    }
}

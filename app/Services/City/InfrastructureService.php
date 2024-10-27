<?php

namespace App\Services\City;

use App\Models\City\Building;

class InfrastructureService
{
    public function scheduleMaintenance($buildingId)
    {
        $building = Building::findOrFail($buildingId);
        // Simula la pianificazione della manutenzione, es. crea log di manutenzione
        $building->last_maintenance_date = now();
        $building->save();
    }

    public function trackInfrastructureEfficiency($districtId)
    {
        $buildings = Building::where('district_id', $districtId)->get();
        $efficiency = $buildings->avg('efficiency');
        return $efficiency;
    }

    public function reportInfrastructureCondition($cityId)
    {
        $buildings = Building::whereHas('district.city', function ($query) use ($cityId) {
            $query->where('id', $cityId);
        })->get();

        $report = [];
        foreach ($buildings as $building) {
            $report[$building->name] = [
                'type' => $building->type,
                'condition' => $building->condition,
            ];
        }
        return $report;
    }
}

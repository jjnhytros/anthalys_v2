<?php

namespace App\Services\CLAIR\Adaptation;

use App\Models\CLAIR;
use App\Models\City\District;
use App\Models\Resource\Resource;

class AdaptationService
{
    protected $type = 'A'; // A per Adaptation

    public function transferResource(District $surplusDistrict, District $deficitDistrict, $resourceId, $quantity)
    {
        $surplusResource = $surplusDistrict->resources()->where('id', $resourceId)->first();

        if ($surplusResource && $surplusResource->availability >= $quantity) {
            $surplusResource->decrement('availability', $quantity);

            $deficitResource = $deficitDistrict->resources()->where('id', $resourceId)->first();
            if ($deficitResource) {
                $deficitResource->increment('availability', $quantity);
            } else {
                $deficitDistrict->resources()->create([
                    'id' => $resourceId,
                    'availability' => $quantity
                ]);
            }

            CLAIR::logActivity($this->type, 'transferResource', [
                'surplus_district_id' => $surplusDistrict->id,
                'deficit_district_id' => $deficitDistrict->id,
                'resource_id' => $resourceId,
                'quantity_transferred' => $quantity,
            ], null, $resourceId, 'transferred', 'Trasferimento risorse completato');
        }
    }

    public function adjustResourcesForNewBuilding(District $district, $building)
    {
        $district->resources()->where('name', 'Energia')->decrement('availability', $building->energy_consumption);
        $district->resources()->where('name', 'Acqua')->decrement('availability', $building->water_consumption);
        $district->resources()->where('name', 'Cibo')->decrement('availability', $building->food_consumption);

        CLAIR::logActivity($this->type, 'adjustResourcesForNewBuilding', [
            'district_id' => $district->id,
            'building_id' => $building->id,
            'energy_consumed' => $building->energy_consumption,
            'water_consumed' => $building->water_consumption,
            'food_consumed' => $building->food_consumption,
        ], null, $district->id, 'adjusted', 'Risorse adattate per il nuovo edificio');
    }
}

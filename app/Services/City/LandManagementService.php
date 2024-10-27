<?php

namespace App\Services\City;

use App\Models\City\City;
use App\Models\City\District;

class LandManagementService
{
    public function allocateLandToDistrict($districtId, $area)
    {
        $district = District::findOrFail($districtId);
        $district->area += $area;
        $district->save();
    }

    public function trackSoilHealth($districtId)
    {
        $district = District::findOrFail($districtId);
        return $district->soil_health;
    }

    public function updateUrbanRuralRatio($cityId, $urbanArea, $ruralArea)
    {
        $city = City::findOrFail($cityId);
        $city->urban_area = $urbanArea;
        $city->rural_area = $ruralArea;
        $city->save();
    }
}

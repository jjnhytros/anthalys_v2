<?php

namespace App\Services\City;

use App\Models\City\District;

class PopulationManagementService
{
    public function registerBirth($districtId)
    {
        $district = District::findOrFail($districtId);
        $district->population += 1;
        $district->save();
    }

    public function registerDeath($districtId)
    {
        $district = District::findOrFail($districtId);
        if ($district->population > 0) {
            $district->population -= 1;
            $district->save();
        }
    }

    public function trackMigration($fromDistrictId, $toDistrictId)
    {
        $fromDistrict = District::findOrFail($fromDistrictId);
        $toDistrict = District::findOrFail($toDistrictId);

        if ($fromDistrict->population > 0) {
            $fromDistrict->population -= 1;
            $toDistrict->population += 1;
            $fromDistrict->save();
            $toDistrict->save();
        }
    }
}

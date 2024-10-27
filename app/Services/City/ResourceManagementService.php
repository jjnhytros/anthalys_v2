<?php

namespace App\Services\City;

use App\Models\City\District;
use App\Models\Resource\Resource;

class ResourceManagementService
{
    public function consumeResource($districtId, $resource, $amount)
    {
        $resource = Resource::where('district_id', $districtId)->where('name', $resource)->first();
        if ($resource && $resource->quantity >= $amount) {
            $resource->quantity -= $amount;
            $resource->save();
        }
    }

    public function checkResourceThreshold($districtId, $resource)
    {
        $resource = Resource::where('district_id', $districtId)->where('name', $resource)->first();
        return $resource && $resource->quantity < $resource->threshold;
    }

    public function generateResourceReport($cityId)
    {
        $districts = District::where('city_id', $cityId)->with('resources')->get();
        $report = [];
        foreach ($districts as $district) {
            foreach ($district->resources as $resource) {
                $report[$district->name][$resource->name] = [
                    'quantity' => $resource->quantity,
                    'threshold' => $resource->threshold,
                ];
            }
        }
        return $report;
    }
}

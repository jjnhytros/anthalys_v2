<?php

namespace App\Services\CLAIR\Resilience;

use App\Models\CLAIR;
use App\Models\City\District;
use App\Models\Resource\EmergencyPlan;

class ResilienceService
{
    protected $type = 'R'; // R per Resilience

    public function activateEmergencyPlan($resourceName)
    {
        $plan = EmergencyPlan::where('resource_name', $resourceName)->first();

        if ($plan) {
            $plan->activate();

            CLAIR::logActivity($this->type, 'activateEmergencyPlan', [
                'resource_name' => $resourceName,
                'plan_id' => $plan->id,
                'status' => 'activated'
            ], $resourceName, $plan->id, 'activated', 'Piano di emergenza attivato');
        }
    }

    public function canSupportNewBuilding(District $district)
    {
        $availableEnergy = $district->resources()->where('name', 'Energia')->value('availability');
        $availableWater = $district->resources()->where('name', 'Acqua')->value('availability');

        $canSupport = $availableEnergy > 100 && $availableWater > 100;

        CLAIR::logActivity($this->type, 'canSupportNewBuilding', [
            'district_id' => $district->id,
            'available_energy' => $availableEnergy,
            'available_water' => $availableWater,
            'can_support' => $canSupport,
        ], null, $district->id, 'evaluated', 'Valutazione di supporto completata');

        return $canSupport;
    }
}

<?php

namespace App\Jobs\Resources;

use App\Facades\CLAIR;
use App\Models\City\District;

class AutomatedResourceTransferJob
{
    protected $governmentId = 2; // ID del governo come mittente

    public function handle()
    {
        // Analizza i pattern di consumo nei distretti e identifica i distretti in deficit
        $deficitDistricts = CLAIR::comprehension()->analyzeConsumptionPatterns();

        foreach ($deficitDistricts as $resourceId => $districtId) {
            $district = District::find($districtId);

            // Trova un distretto con surplus della risorsa
            $surplusDistrict = District::whereHas('resources', function ($query) use ($resourceId) {
                $query->where('id', $resourceId)->where('availability', '>', 60);
            })->first();

            if ($surplusDistrict) {
                // Esegui il trasferimento della risorsa utilizzando il servizio di Adattamento di CLAIR
                CLAIR::adaptation()->transferResource($surplusDistrict, $district, $resourceId, 24);

                // Invia una notifica al distretto ricevente usando il servizio di Integrazione di CLAIR
                CLAIR::integration()->sendNotification(
                    $this->governmentId,
                    $district->manager_id,
                    'Trasferimento Automatico di Risorse',
                    "È stato completato un trasferimento automatico della risorsa {$resourceId} al distretto {$district->name} da {$surplusDistrict->name}."
                );
            }
        }
    }
}

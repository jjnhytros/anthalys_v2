<?php

namespace App\Http\Controllers\City;

use App\Models\CLAIR;
use App\Models\City\Citizen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubsidyController extends Controller
{
    public function distributeSubsidies()
    {
        $lowIncomeCitizens = Citizen::where('income', '<', 6000)->get(); // Redditi bassi
        $subsidyAmount = 500; // Sussidio standard

        foreach ($lowIncomeCitizens as $citizen) {
            $citizen->cash += $subsidyAmount;
            $citizen->save();

            // Notifica al cittadino del sussidio ricevuto
            $this->notifyCitizenOfSubsidy($citizen, $subsidyAmount);

            // Registra l'attività di distribuzione del sussidio
            CLAIR::logActivity(
                'S', // Tipo di attività per Subsidy
                'distributeSubsidies',
                'Distribuzione sussidio per cittadino a basso reddito',
                ['citizen_id' => $citizen->id, 'subsidy_amount' => $subsidyAmount]
            );
        }

        return response()->json(['message' => 'Sussidi distribuiti con successo.']);
    }

    private function notifyCitizenOfSubsidy($citizen, $subsidy)
    {
        // Notifica il cittadino del sussidio
        $citizen->notify(new CitizenSubsidyNotification(
            'Hai ricevuto un sussidio di ' . number_format($subsidy, 2) . ' AA per il supporto del governo.'
        ));
    }
}

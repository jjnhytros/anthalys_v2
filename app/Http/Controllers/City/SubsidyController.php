<?php

namespace App\Http\Controllers\City;

use App\Models\City\Citizen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubsidyController extends Controller
{
    public function distributeSubsidies()
    {
        $lowIncomeCitizens = Citizen::where('income', '<', 6000)->get(); // Redditi bassi

        foreach ($lowIncomeCitizens as $citizen) {
            $subsidy = 500; // Sussidio standard
            $citizen->cash += $subsidy;
            $citizen->save();

            // $this->notifyCitizenOfSubsidy($citizen, $subsidy);
        }
    }

    // private function notifyCitizenOfSubsidy($citizen, $subsidy)
    // {
    //     // Notifica il cittadino del sussidio
    //     $citizen->notify(new CitizenSubsidyNotification('Hai ricevuto un sussidio di ' . number_format($subsidy, 2) . ' AA per il supporto del governo.'));
    // }

}

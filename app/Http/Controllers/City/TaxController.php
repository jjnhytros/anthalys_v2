<?php

namespace App\Http\Controllers\City;

use App\Models\City\Citizen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaxController extends Controller
{
    public function imposeFinesForNonCompliance()
    {
        $citizens = Citizen::where('taxes_due', '>', 1000)->get(); // Cittadini con tasse arretrate superiori a 1000 AA

        foreach ($citizens as $citizen) {
            $fine = $citizen->taxes_due * 0.1; // Sanzione del 10% delle tasse arretrate
            $citizen->cash -= $fine;
            $citizen->save();

            // $this->notifyCitizenOfFine($citizen, $fine);
        }
    }

    // private function notifyCitizenOfFine($citizen, $fine)
    // {
    //     // Notifica il cittadino della sanzione
    //     $citizen->notify(new CitizenFineNotification('Hai ricevuto una sanzione di ' . number_format($fine, 2) . ' AA per il mancato pagamento delle tasse.'));
    // }
}

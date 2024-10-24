<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\City\Citizen;
use Illuminate\Console\Command;
use App\Models\City\Transaction;

class PayPensions extends Command
{
    protected $signature = 'anthalys:pay-pensions';
    protected $description = 'Paga le pensioni ai cittadini in pensione';

    public function handle()
    {
        // Recupera l'utente "government" per il bilancio
        $government = User::where('name', 'government')->first();
        if (!$government) {
            $this->error('Utente "government" non trovato.');
            return;
        }

        // Recupera tutti i cittadini in pensione
        $retiredCitizens = Citizen::where('is_retired', true)->get();
        $totalPensionsPaid = 0;

        foreach ($retiredCitizens as $citizen) {
            // Calcola la pensione del cittadino
            $pensionAmount = $citizen->calculatePension();

            // Versa la pensione al cittadino
            $citizen->cash += $pensionAmount;
            $citizen->save();

            // Detrai la pensione dal bilancio del governo
            $government->cash -= $pensionAmount;

            // Registra la transazione
            Transaction::create([
                'citizen_id' => $citizen->id,
                'amount' => $pensionAmount,
                'type' => 'pension',
                'description' => 'Pagamento della pensione',
            ]);

            $totalPensionsPaid += $pensionAmount;
        }

        // Aggiorna il bilancio del governo
        $government->save();

        $this->info('Totale pensioni pagate: ' . athel($totalPensionsPaid));
    }
}

<?php

namespace App\Console\Commands;

use App\Models\City\Citizen;
use Illuminate\Console\Command;

class UpdateYearsOfService extends Command
{
    protected $signature = 'anthalys:citizens-update-years-of-service';
    protected $description = 'Aggiorna gli anni di servizio per i cittadini attivi';

    public function handle()
    {
        // Recupera i cittadini attivi (non in pensione)
        $citizens = Citizen::where('is_retired', false)->get();

        foreach ($citizens as $citizen) {
            // Incrementa gli anni di servizio
            $citizen->years_of_service += 1;

            // Controlla se il cittadino ha raggiunto l'età pensionabile
            if ($citizen->years_of_service >= 20) {
                // Imposta il cittadino come in pensione
                $citizen->is_retired = true;
                $this->info('Cittadino ' . $citizen->name . ' è andato in pensione.');
            }

            // Salva le modifiche
            $citizen->save();
        }

        $this->info('Anni di servizio aggiornati per tutti i cittadini.');
    }
}

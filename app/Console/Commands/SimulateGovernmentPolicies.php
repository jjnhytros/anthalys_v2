<?php

namespace App\Console\Commands;

use App\Models\City\Citizen;
use Illuminate\Console\Command;

class SimulateGovernmentPolicies extends Command
{
    protected $signature = 'simulate:government-policies';
    protected $description = 'Simula l\'applicazione delle politiche fiscali e dei sussidi';

    public function handle()
    {
        $citizens = Citizen::all();

        foreach ($citizens as $citizen) {
            $citizen->calculateTaxes(); // Calcolo delle tasse
            $citizen->calculateSubsidies(); // Calcolo dei sussidi
        }

        $this->info('Simulazione delle politiche governative completata con successo.');
    }
}

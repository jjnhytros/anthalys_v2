<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Citizen;
use App\Models\Transaction;
use Illuminate\Console\Command;

class CollectTaxes extends Command
{
    protected $signature = 'simulate:taxescollect';
    protected $description = 'Raccoglie le tasse dagli abitanti attivi lavorativamente e aggiorna il campo cash del governo.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Recupera l'utente "government"
        $government = User::where('name', 'government')->first();
        if (!$government) {
            $this->error('Utente "government" non trovato.');
            return;
        }

        // Definizione delle aliquote fiscali per fasce di reddito
        $taxBrackets = [
            ['limit' => 3000, 'rate' => 0.015],
            ['limit' => 6000, 'rate' => 0.03],
            ['limit' => 12000, 'rate' => 0.045],
            ['limit' => 15000, 'rate' => 0.06],
            ['limit' => 18000, 'rate' => 0.075],
            ['limit' => 21000, 'rate' => 0.09],
            ['limit' => 24000, 'rate' => 0.105],
            ['limit' => 27000, 'rate' => 0.12],
            ['limit' => 30000, 'rate' => 0.135],
            ['limit' => 33000, 'rate' => 0.15],
            ['limit' => 36000, 'rate' => 0.165],
            ['limit' => 39000, 'rate' => 0.18],
            ['limit' => 42000, 'rate' => 0.195],
            ['limit' => 45000, 'rate' => 0.21],
            ['limit' => 48000, 'rate' => 0.225],
            ['limit' => 51000, 'rate' => 0.24],
            ['limit' => 144000, 'rate' => 0.28],

        ];

        // Tassa sul consumo di risorse (1% aggiuntivo per ogni 1000 unità di risorse consumate)
        $resourceTaxRate = 0.01; // 1%

        // Recuperiamo tutti i cittadini impiegati
        $citizens = Citizen::where('is_employed', true)->get();
        if ($citizens->isEmpty()) {
            $this->error('Nessun cittadino impiegato trovato.');
            return;
        }

        // Sommiamo manualmente le tasse da raccogliere
        $taxesCollected = 0;

        foreach ($citizens as $citizen) {
            // Calcolo tasse sul reddito
            $incomeTax = $this->calculateIncomeTax($citizen->income, $taxBrackets);

            // Mostra il reddito per debugging
            $this->info('Reddito del cittadino: ' . $citizen->income . ' €. Tasse calcolate: ' . $incomeTax);

            // Calcolo tassa sul consumo di risorse
            $resourceConsumption = 0;

            foreach ($citizens as $citizen) {
                // Calcolo tasse sul reddito
                $incomeTax = $this->calculateIncomeTax($citizen->income, $taxBrackets);

                // Calcolo tassa sul consumo di risorse
                $resourceConsumption = 0;
                if ($citizen->residentialBuilding) {
                    $resourceConsumption += $citizen->residentialBuilding->energy_consumption;
                    $resourceConsumption += $citizen->residentialBuilding->water_consumption;
                    $resourceConsumption += $citizen->residentialBuilding->food_consumption;
                }
                if ($citizen->workBuilding) {
                    $resourceConsumption += $citizen->workBuilding->energy_consumption;
                    $resourceConsumption += $citizen->workBuilding->water_consumption;
                }

                $resourceTax = ($resourceConsumption / 1000) * $resourceTaxRate;
                $totalTax = $incomeTax + $resourceTax;

                $taxesCollected += $totalTax;

                // Salva la transazione di tassa
                Transaction::create([
                    'type' => 'income',
                    'amount' => $totalTax,
                    'description' => 'Tasse raccolte dai cittadini',
                ]);

                $this->info('Tasse raccolte dal cittadino: ' . $totalTax . ' €');
            }

            // Aggiorna il bilancio del governo
            $government->cash += $taxesCollected;
            $government->save();

            $this->info('Tasse totali raccolte: ' . $taxesCollected . ' €. Nuovo bilancio del governo: ' . $government->cash);
        }
    }

    private function calculateIncomeTax($income, $taxBrackets)
    {
        $tax = 0;
        foreach ($taxBrackets as $bracket) {
            if ($bracket['limit'] === null || $income <= $bracket['limit']) {
                $tax += $income * $bracket['rate'];
                break;
            }
            $tax += $bracket['limit'] * $bracket['rate'];
            $income -= $bracket['limit'];
        }
        return $tax;
    }
}

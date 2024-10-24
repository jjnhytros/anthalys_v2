<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\City\Citizen;
use Illuminate\Console\Command;
use App\Models\City\Transaction;

class CollectTaxes extends Command
{
    protected $signature = 'anthalys:taxes-collect';
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

        // Recupera le fasce fiscali e la tassa sulle risorse
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

        $resourceTaxRate = 0.01; // Tassa sulle risorse

        // Recupera tutti i cittadini impiegati
        $citizens = Citizen::where('is_employed', true)->get();
        if ($citizens->isEmpty()) {
            $this->error('Nessun cittadino impiegato trovato.');
            return;
        }

        // Somma le tasse raccolte
        $totalTaxes = 0;

        foreach ($citizens as $citizen) {
            // Usa il metodo calculateTaxes per calcolare e versare le tasse
            $taxCollected = $citizen->calculateTaxes($taxBrackets, $resourceTaxRate);
            $totalTaxes += $taxCollected;

            // Registra la transazione della tassa raccolta
            Transaction::create([
                'citizen_id' => $citizen->id,
                'type' => 'tax',
                'amount' => $taxCollected,
                'description' => 'Tasse raccolte dal cittadino ' . $citizen->name,
            ]);

            $this->info('Tasse raccolte dal cittadino: ' . athel($taxCollected));
        }

        $this->info('Tasse totali raccolte: ' . athel($totalTaxes));
    }
}

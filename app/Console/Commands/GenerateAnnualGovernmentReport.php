<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Models\City\Transaction;

class GenerateAnnualGovernmentReport extends Command
{
    protected $signature = 'anthalys:generate-government-report';
    protected $description = 'Genera il report annuale del bilancio governativo';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Recuperiamo l'utente "government" e il bilancio iniziale
        $government = User::where('name', 'government')->first();
        $initial_balance = $government->cash;

        // Calcolo delle tasse raccolte
        $total_taxes = Transaction::where('type', 'tax')->sum('amount');

        // Calcolo dei sussidi pagati
        $total_subsidies = Transaction::where('type', 'expense')->sum('amount');
        $healthcare_subsidies = Transaction::where('type', 'healthcare_subsidy')->sum('amount');
        $totalPensionsPaid = Transaction::where('type', 'pension')
            ->sum('amount');

        // Calcolo del saldo finale
        $final_balance = $government->cash;

        // Mostra il report in console
        $this->info('Bilancio Governativo Annuale:');
        $this->info("Bilancio Iniziale: " . athel($initial_balance));
        $this->info("Tasse Raccolte: " . athel($total_taxes));
        $this->info("Sussidi Pagati: " . athel($total_subsidies));
        $this->info("Sussidi Sanitari Pagati: " . athel($healthcare_subsidies));
        $this->info("Pensioni Pagate: " . athel($totalPensionsPaid));
        $this->info("Bilancio Finale: " . athel($final_balance));

        // Salviamo il report in un file
        $report = [
            'bilancio_iniziale' => $initial_balance,
            'tasse_raccolte' => $total_taxes,
            'sussidi_pagati' => $total_subsidies,
            'bilancio_finale' => $final_balance,
        ];

        $path = storage_path('app/reports/government_report_' . now()->format('Y') . '.json');
        file_put_contents($path, json_encode($report));

        $this->info("Report salvato in {$path}");
    }
}

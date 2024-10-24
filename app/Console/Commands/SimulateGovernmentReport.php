<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\City\Transaction;

class SimulateGovernmentReport extends Command
{
    protected $signature = 'simulate:government-report {period=daily}';
    protected $description = 'Genera un report delle entrate fiscali e delle spese governative per un determinato periodo';

    public function handle()
    {
        $period = $this->argument('period');
        $startDate = now()->startOfDay();

        if ($period == 'weekly') {
            $startDate = now()->subWeek()->startOfDay();
        } elseif ($period == 'monthly') {
            $startDate = now()->subMonth()->startOfDay();
        }

        $transactions = Transaction::where('created_at', '>=', $startDate)->get();

        $income = $transactions->where('type', 'income')->sum('amount');
        $expenses = $transactions->where('type', 'expense')->sum('amount');

        $this->info("Report $period:");
        $this->info("Entrate fiscali: " . number_format($income, 2) . ' AA');
        $this->info("Spese governative: " . number_format($expenses, 2) . ' AA');
    }
}

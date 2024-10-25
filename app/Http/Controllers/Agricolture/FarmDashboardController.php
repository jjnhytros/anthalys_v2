<?php

namespace App\Http\Controllers\Agricolture;

use App\Models\City\Event;
use Illuminate\Http\Request;
use App\Models\Agricolture\Farm;
use App\Http\Controllers\Controller;

class FarmDashboardController extends Controller
{
    public function index()
    {
        // Visualizza la dashboard
        return view('dashboard.farm');
    }

    public function getStats()
    {
        // Ottieni i dati della produzione e distribuzione in tempo reale
        $farms = Farm::with(['crops', 'animals', 'greenhouses'])->get();
        $active_events = Event::where('active', true)->get();

        // Calcola le statistiche (esempio per la produzione totale)
        $total_production = $farms->sum(function ($farm) {
            return $farm->crops->sum('yield') + $farm->animals->sum('yield');
        });

        $distribution_efficiency = rand(80, 100); // Simulazione per esempio

        return response()->json([
            'total_production' => $total_production,
            'distribution_efficiency' => $distribution_efficiency,
            'farm_count' => $farms->count(),
        ]);
    }
}

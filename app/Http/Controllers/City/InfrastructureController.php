<?php

namespace App\Http\Controllers\City;

use App\Models\User;
use App\Models\CLAIR;
use Illuminate\Http\Request;
use App\Models\City\District;
use App\Models\City\Transaction;
use App\Models\City\Infrastructure;
use App\Http\Controllers\Controller;
use App\Models\City\InfrastructureMaintenanceHistory;

class InfrastructureController extends Controller
{
    public function index(District $district)
    {
        $infrastructures = $district->infrastructures;

        // Log dell'attività di visualizzazione delle infrastrutture
        CLAIR::logActivity(
            'C',
            'index',
            'Visualizzazione delle infrastrutture per il distretto',
            ['district_id' => $district->id]
        );

        return view('infrastructures.index', compact('district', 'infrastructures'));
    }

    public function create(District $district)
    {
        // Log dell'attività di creazione di un'infrastruttura
        CLAIR::logActivity(
            'C',
            'create',
            'Creazione di una nuova infrastruttura per il distretto',
            ['district_id' => $district->id]
        );

        return view('infrastructures.create', compact('district'));
    }

    public function store(Request $request, District $district)
    {
        $infrastructure = $district->infrastructures()->create($request->all());

        // Log dell'attività di salvataggio dell'infrastruttura
        CLAIR::logActivity(
            'A',
            'store',
            'Salvataggio della nuova infrastruttura',
            ['infrastructure_id' => $infrastructure->id]
        );

        return redirect()->route('districts.infrastructures.index', $district);
    }

    public function maintain(Infrastructure $infrastructure)
    {
        $government = User::where('name', 'government')->first();
        $baseMaintenanceCost = 1000;
        $wearPercentage = 1 - $infrastructure->condition;
        $maintenanceCost = $baseMaintenanceCost * $wearPercentage;

        if ($wearPercentage == 0) {
            return back()->with('info', 'L\'infrastruttura è già in ottime condizioni. Non è necessaria la manutenzione.');
        }

        if ($government->cash < $maintenanceCost) {
            return back()->withErrors(['error' => 'Fondi insufficienti per effettuare la manutenzione. Costo: ' . $maintenanceCost . ' €.']);
        }

        $government->cash -= $maintenanceCost;
        $government->save();
        $infrastructure->condition = 1.00;
        $infrastructure->save();

        InfrastructureMaintenanceHistory::create([
            'infrastructure_id' => $infrastructure->id,
            'maintained_at' => now(),
        ]);
        Transaction::create([
            'type' => 'expense',
            'amount' => $maintenanceCost,
            'description' => 'Manutenzione di ' . $infrastructure->name,
        ]);

        // Log dell'attività di manutenzione dell'infrastruttura
        CLAIR::logActivity(
            'R',
            'maintain',
            'Manutenzione dell\'infrastruttura',
            [
                'infrastructure_id' => $infrastructure->id,
                'cost' => $maintenanceCost,
                'remaining_cash' => $government->cash
            ]
        );

        return back()->with('success', 'Manutenzione eseguita con successo! Costo: ' . $maintenanceCost . ' €. Bilancio rimanente: ' . $government->cash . ' €');
    }

    public function history(Infrastructure $infrastructure)
    {
        $maintenanceHistory = $infrastructure->maintenanceHistory;

        // Log dell'attività di visualizzazione dello storico della manutenzione
        CLAIR::logActivity(
            'C',
            'history',
            'Visualizzazione dello storico di manutenzione per l\'infrastruttura',
            ['infrastructure_id' => $infrastructure->id]
        );

        return view('infrastructures.history', compact('infrastructure', 'maintenanceHistory'));
    }

    public function applyDeterioration()
    {
        $infrastructures = Infrastructure::all();

        foreach ($infrastructures as $infrastructure) {
            $deterioration = mt_rand(1, 5) * 1e-6;
            $infrastructure->condition = max($infrastructure->condition - $deterioration, 0);
            $infrastructure->efficiency = $infrastructure->condition;
            $infrastructure->save();

            // Log dell'attività di deterioramento per ciascuna infrastruttura
            CLAIR::logActivity(
                'R',
                'applyDeterioration',
                'Applicazione del deterioramento per l\'infrastruttura',
                ['infrastructure_id' => $infrastructure->id, 'deterioration' => $deterioration]
            );
        }

        return response()->json(['success' => true]);
    }

    public function monitorInfrastructureEfficiency(Infrastructure $infrastructure)
    {
        if ($infrastructure->efficiency < 0.8) {
            $this->optimizeInfrastructure($infrastructure);
        }

        // Log dell'attività di monitoraggio dell'efficienza dell'infrastruttura
        CLAIR::logActivity(
            'C',
            'monitorInfrastructureEfficiency',
            'Monitoraggio dell\'efficienza dell\'infrastruttura',
            ['infrastructure_id' => $infrastructure->id, 'efficiency' => $infrastructure->efficiency]
        );

        return response()->json(['efficiency' => $infrastructure->efficiency]);
    }

    private function optimizeInfrastructure(Infrastructure $infrastructure)
    {
        $infrastructure->efficiency += 0.1;
        $infrastructure->save();

        $government = User::where('name', 'government')->first();

        // Log dell'attività di ottimizzazione dell'infrastruttura
        CLAIR::logActivity(
            'A',
            'optimizeInfrastructure',
            'Ottimizzazione dell\'infrastruttura',
            ['infrastructure_id' => $infrastructure->id, 'new_efficiency' => $infrastructure->efficiency]
        );
    }
}

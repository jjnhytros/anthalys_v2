<?php

namespace App\Http\Controllers\City;

use App\Models\User;
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
        return view('infrastructures.index', compact('district', 'infrastructures'));
    }

    public function create(District $district)
    {
        return view('infrastructures.create', compact('district'));
    }

    public function store(Request $request, District $district)
    {
        $district->infrastructures()->create($request->all());
        return redirect()->route('districts.infrastructures.index', $district);
    }

    public function maintain(Infrastructure $infrastructure)
    {
        // Recupera l'utente "government"
        $government = User::where('name', 'government')->first();

        // Costo base per la manutenzione
        $baseMaintenanceCost = 1000; // Costo base fisso (può variare)

        // Calcola l'usura dell'infrastruttura
        $wearPercentage = 1 - $infrastructure->condition; // Usura in percentuale (es. 20% usura se condition = 0.8)

        // Calcola il costo della manutenzione in base alla percentuale di usura
        $maintenanceCost = $baseMaintenanceCost * $wearPercentage;

        // Se l'usura è zero (condizione = 1.00), non è necessaria la manutenzione
        if ($wearPercentage == 0) {
            return back()->with('info', 'L\'infrastruttura è già in ottime condizioni. Non è necessaria la manutenzione.');
        }

        // Verifica se il governo ha fondi sufficienti per pagare la manutenzione
        if ($government->cash < $maintenanceCost) {
            return back()->withErrors(['error' => 'Fondi insufficienti per effettuare la manutenzione. Costo: ' . $maintenanceCost . ' €.']);
        }

        // Riduci il bilancio del governo
        $government->cash -= $maintenanceCost;
        $government->save();

        // Ripristina la condizione dell'infrastruttura al 100%
        $infrastructure->condition = 1.00;
        $infrastructure->save();

        // Registra la manutenzione nello storico
        InfrastructureMaintenanceHistory::create([
            'infrastructure_id' => $infrastructure->id,
            'maintained_at' => now(),
        ]);
        Transaction::create([
            'type' => 'expense',
            'amount' => $maintenanceCost,
            'description' => 'Manutenzione di ' . $infrastructure->name,
        ]);

        return back()->with('success', 'Manutenzione eseguita con successo! Costo: ' . $maintenanceCost . ' €. Bilancio rimanente: ' . $government->cash . ' €');
    }

    public function history(Infrastructure $infrastructure)
    {
        $maintenanceHistory = $infrastructure->maintenanceHistory;
        return view('infrastructures.history', compact('infrastructure', 'maintenanceHistory'));
    }
    public function applyDeterioration()
    {
        // Recupera tutte le infrastrutture
        $infrastructures = Infrastructure::all();

        // Applica il deterioramento dinamico a ciascuna infrastruttura
        foreach ($infrastructures as $infrastructure) {
            // Genera un valore casuale tra 1e-6 e 5e-6
            $deterioration = mt_rand(1, 5) * 1e-6;

            // Riduci la condizione dell'infrastruttura (ma non scendere sotto 0)
            $infrastructure->condition = max($infrastructure->condition - $deterioration, 0);
            $infrastructure->efficiency = $infrastructure->condition;

            // Salva l'infrastruttura con la condizione aggiornata
            $infrastructure->save();
        }

        return response()->json(['success' => true]);
    }
    public function monitorInfrastructureEfficiency($infrastructure)
    {
        // Controlliamo se l'infrastruttura è sotto una certa soglia di efficienza
        if ($infrastructure->efficiency < 0.8) {
            $this->optimizeInfrastructure($infrastructure);
        }

        return response()->json(['efficiency' => $infrastructure->efficiency]);
    }
    private function optimizeInfrastructure($infrastructure)
    {
        // Ottimizza l'infrastruttura migliorando la sua efficienza
        $infrastructure->efficiency += 0.1;
        $infrastructure->save();

        // Notifica il governo dell'ottimizzazione
        $government = User::where('name', 'government')->first();
        // $government->notify(new GovernmentNotification('L\'infrastruttura ' . $infrastructure->name . ' è stata ottimizzata.'));
    }
}

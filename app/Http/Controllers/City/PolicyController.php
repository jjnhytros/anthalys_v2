<?php

namespace App\Http\Controllers\City;

use App\Models\CLAIR;
use App\Models\City\Policy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PolicyController extends Controller
{
    /**
     * Mostra l'elenco delle politiche attive
     */
    public function index()
    {
        $policies = Policy::where('active', true)->get();

        // Registra l'attività di visualizzazione delle politiche attive
        CLAIR::logActivity(
            'C',
            'index',
            'Visualizzazione delle politiche attive',
            ['active_policies_count' => $policies->count()]
        );

        return view('policies.index', compact('policies'));
    }

    /**
     * Aggiunge una nuova politica
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:tax,subsidy,regulation',
            'rate' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        Policy::create($validatedData);

        // Registra l'attività di aggiunta di una politica
        CLAIR::logActivity(
            'A',
            'store',
            'Aggiunta di una nuova politica',
            ['policy_data' => $validatedData]
        );

        return redirect()->route('policies.index')->with('success', 'Politica aggiunta con successo');
    }

    /**
     * Modifica una politica esistente
     */
    public function update(Request $request, Policy $policy)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:tax,subsidy,regulation',
            'rate' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'active' => 'required|boolean',
        ]);

        $policy->update($validatedData);

        // Registra l'attività di aggiornamento di una politica
        CLAIR::logActivity(
            'R',
            'update',
            'Aggiornamento di una politica esistente',
            ['policy_id' => $policy->id, 'updated_data' => $validatedData]
        );

        return redirect()->route('policies.index')->with('success', 'Politica aggiornata con successo');
    }

    /**
     * Elimina una politica
     */
    public function destroy(Policy $policy)
    {
        $policyId = $policy->id;
        $policy->delete();

        // Registra l'attività di eliminazione di una politica
        CLAIR::logActivity(
            'R',
            'destroy',
            'Eliminazione di una politica',
            ['policy_id' => $policyId]
        );

        return redirect()->route('policies.index')->with('success', 'Politica eliminata con successo');
    }
}

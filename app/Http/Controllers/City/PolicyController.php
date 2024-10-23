<?php

namespace App\Http\Controllers\City;

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
        return view('policies.index', compact('policies'));
    }

    /**
     * Aggiunge una nuova politica
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:tax,subsidy,regulation',
            'rate' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        Policy::create($request->all());

        return redirect()->route('policies.index')->with('success', 'Politica aggiunta con successo');
    }

    /**
     * Modifica una politica esistente
     */
    public function update(Request $request, Policy $policy)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:tax,subsidy,regulation',
            'rate' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'active' => 'required|boolean',
        ]);

        $policy->update($request->all());

        return redirect()->route('policies.index')->with('success', 'Politica aggiornata con successo');
    }

    /**
     * Elimina una politica
     */
    public function destroy(Policy $policy)
    {
        $policy->delete();

        return redirect()->route('policies.index')->with('success', 'Politica eliminata con successo');
    }
}

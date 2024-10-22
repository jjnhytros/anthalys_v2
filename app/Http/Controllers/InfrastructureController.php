<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;
use App\Models\Infrastructure;
use App\Models\InfrastructureMaintenanceHistory;

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
        // Logica per eseguire la manutenzione
        $infrastructure->condition = 1; // Reset della condizione a 100%
        $infrastructure->save();

        // Registra la manutenzione nello storico
        InfrastructureMaintenanceHistory::create([
            'infrastructure_id' => $infrastructure->id,
            'maintained_at' => now(),
        ]);

        return back()->with('success', 'Manutenzione eseguita con successo!');
    }
    public function history(Infrastructure $infrastructure)
    {
        $maintenanceHistory = $infrastructure->maintenanceHistory;
        return view('infrastructures.history', compact('infrastructure', 'maintenanceHistory'));
    }
}

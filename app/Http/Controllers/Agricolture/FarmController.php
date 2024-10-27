<?php

namespace App\Http\Controllers\Agricolture;

use App\Models\CLAIR;
use Illuminate\Http\Request;
use App\Models\Agricolture\Farm;
use App\Http\Controllers\Controller;

class FarmController extends Controller
{
    public function index()
    {
        $farms = Farm::all();

        // Registra l'attività di visualizzazione dell'elenco delle fattorie
        CLAIR::logActivity(
            'A', // Tipo di attività per Agricultural
            'index',
            'Visualizzazione dell\'elenco delle fattorie',
            ['farm_count' => $farms->count()]
        );

        return view('farms.index', compact('farms'));
    }

    public function create()
    {
        // Log dell'attività di accesso al modulo di creazione
        CLAIR::logActivity(
            'A',
            'create',
            'Accesso al modulo di creazione di una nuova fattoria',
            []
        );

        return view('farms.create');
    }

    public function store(Request $request)
    {
        $farm = Farm::create($request->all());

        // Log dell'attività di creazione di una fattoria
        CLAIR::logActivity(
            'A',
            'store',
            'Creazione di una nuova fattoria',
            ['farm_id' => $farm->id, 'farm_name' => $farm->name]
        );

        return redirect()->route('farms.index');
    }

    public function show(Farm $farm)
    {
        // Log dell'attività di visualizzazione dei dettagli della fattoria
        CLAIR::logActivity(
            'A',
            'show',
            'Visualizzazione dei dettagli della fattoria',
            ['farm_id' => $farm->id]
        );

        return view('farms.show', compact('farm'));
    }

    public function edit(Farm $farm)
    {
        // Log dell'attività di accesso al modulo di modifica della fattoria
        CLAIR::logActivity(
            'A',
            'edit',
            'Accesso al modulo di modifica della fattoria',
            ['farm_id' => $farm->id]
        );

        return view('farms.edit', compact('farm'));
    }

    public function update(Request $request, Farm $farm)
    {
        $farm->update($request->all());

        // Log dell'attività di aggiornamento della fattoria
        CLAIR::logActivity(
            'A',
            'update',
            'Aggiornamento della fattoria',
            ['farm_id' => $farm->id, 'updated_data' => $request->all()]
        );

        return redirect()->route('farms.index');
    }

    public function destroy(Farm $farm)
    {
        $farmId = $farm->id;
        $farm->delete();

        // Log dell'attività di eliminazione della fattoria
        CLAIR::logActivity(
            'A',
            'destroy',
            'Eliminazione della fattoria',
            ['farm_id' => $farmId]
        );

        return redirect()->route('farms.index');
    }
}

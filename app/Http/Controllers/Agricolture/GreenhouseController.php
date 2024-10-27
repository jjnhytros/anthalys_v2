<?php

namespace App\Http\Controllers\Agricolture;

use App\Models\CLAIR;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Agricolture\Greenhouse;

class GreenhouseController extends Controller
{
    public function index()
    {
        // Log dell'attività di visualizzazione della lista di serre
        CLAIR::logActivity(
            'A', // Categoria Agricultural
            'index',
            'Accesso alla lista delle serre',
            []
        );

        $greenhouses = Greenhouse::all();
        return view('greenhouses.index', compact('greenhouses'));
    }

    public function create()
    {
        // Log dell'attività di accesso alla creazione di una nuova serra
        CLAIR::logActivity(
            'A',
            'create',
            'Accesso alla creazione di una nuova serra',
            []
        );

        return view('greenhouses.create');
    }

    public function store(Request $request)
    {
        Greenhouse::create($request->all());

        // Log dell'attività di salvataggio di una nuova serra
        CLAIR::logActivity(
            'A',
            'store',
            'Creazione di una nuova serra',
            ['data' => $request->all()]
        );

        return redirect()->route('greenhouses.index');
    }

    public function show(Greenhouse $greenhouse)
    {
        // Log dell'attività di visualizzazione della serra specifica
        CLAIR::logActivity(
            'A',
            'show',
            'Visualizzazione dettagli della serra',
            ['greenhouse_id' => $greenhouse->id]
        );

        return view('greenhouses.show', compact('greenhouse'));
    }

    public function edit(Greenhouse $greenhouse)
    {
        // Log dell'attività di accesso alla modifica della serra
        CLAIR::logActivity(
            'A',
            'edit',
            'Accesso alla modifica della serra',
            ['greenhouse_id' => $greenhouse->id]
        );

        return view('greenhouses.edit', compact('greenhouse'));
    }

    public function update(Request $request, Greenhouse $greenhouse)
    {
        $greenhouse->update($request->all());

        // Log dell'attività di aggiornamento della serra
        CLAIR::logActivity(
            'A',
            'update',
            'Aggiornamento dei dettagli della serra',
            ['greenhouse_id' => $greenhouse->id, 'data' => $request->all()]
        );

        return redirect()->route('greenhouses.index');
    }

    public function destroy(Greenhouse $greenhouse)
    {
        $greenhouse->delete();

        // Log dell'attività di eliminazione della serra
        CLAIR::logActivity(
            'A',
            'destroy',
            'Eliminazione della serra',
            ['greenhouse_id' => $greenhouse->id]
        );

        return redirect()->route('greenhouses.index');
    }
}

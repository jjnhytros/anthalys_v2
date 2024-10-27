<?php

namespace App\Http\Controllers\City;

use App\Models\CLAIR;
use App\Models\City\Drone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DroneController extends Controller
{
    public function index()
    {
        $drones = Drone::all();

        // Log attività per l'accesso alla lista dei droni
        CLAIR::logActivity(
            'C',
            'index',
            'Accesso alla lista di tutti i droni',
            ['total_drones' => $drones->count()]
        );

        return view('drones.index', compact('drones'));
    }

    public function create()
    {
        // Log attività per la visualizzazione della pagina di creazione
        CLAIR::logActivity(
            'C',
            'create',
            'Accesso alla pagina di creazione di un nuovo drone',
            []
        );

        return view('drones.create');
    }

    public function store(Request $request)
    {
        $drone = Drone::create($request->all());

        // Log attività per la creazione di un nuovo drone
        CLAIR::logActivity(
            'A',
            'store',
            'Creazione di un nuovo drone',
            ['drone_id' => $drone->id]
        );

        return redirect()->route('drones.index');
    }

    public function show(Drone $drone)
    {
        // Log attività per la visualizzazione dei dettagli di un drone
        CLAIR::logActivity(
            'C',
            'show',
            'Visualizzazione dei dettagli del drone',
            ['drone_id' => $drone->id]
        );

        return view('drones.show', compact('drone'));
    }

    public function edit(Drone $drone)
    {
        // Log attività per la visualizzazione della pagina di modifica del drone
        CLAIR::logActivity(
            'C',
            'edit',
            'Accesso alla pagina di modifica del drone',
            ['drone_id' => $drone->id]
        );

        return view('drones.edit', compact('drone'));
    }

    public function update(Request $request, Drone $drone)
    {
        $drone->update($request->all());

        // Log attività per l'aggiornamento delle informazioni del drone
        CLAIR::logActivity(
            'A',
            'update',
            'Aggiornamento dei dettagli del drone',
            ['drone_id' => $drone->id]
        );

        return redirect()->route('drones.index');
    }

    public function destroy(Drone $drone)
    {
        $droneId = $drone->id;
        $drone->delete();

        // Log attività per la cancellazione del drone
        CLAIR::logActivity(
            'R',
            'destroy',
            'Cancellazione del drone',
            ['drone_id' => $droneId]
        );

        return redirect()->route('drones.index');
    }
}

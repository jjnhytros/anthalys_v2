<?php

namespace App\Http\Controllers\Agricolture;

use App\Models\CLAIR;
use Illuminate\Http\Request;
use App\Models\Agricolture\Sensor;
use App\Http\Controllers\Controller;

class SensorController extends Controller
{
    public function index()
    {
        // Log dell'attività di visualizzazione della lista dei sensori
        CLAIR::logActivity(
            'A', // Categoria per Agricultural o un'altra a seconda dell'uso dei sensori
            'index',
            'Accesso alla lista dei sensori',
            []
        );

        $sensors = Sensor::all();
        return view('sensors.index', compact('sensors'));
    }

    public function create()
    {
        // Log dell'attività di accesso alla creazione di un nuovo sensore
        CLAIR::logActivity(
            'A',
            'create',
            'Accesso alla creazione di un nuovo sensore',
            []
        );

        return view('sensors.create');
    }

    public function store(Request $request)
    {
        Sensor::create($request->all());

        // Log dell'attività di salvataggio di un nuovo sensore
        CLAIR::logActivity(
            'A',
            'store',
            'Creazione di un nuovo sensore',
            ['data' => $request->all()]
        );

        return redirect()->route('sensors.index');
    }

    public function show(Sensor $sensor)
    {
        // Log dell'attività di visualizzazione del sensore specifico
        CLAIR::logActivity(
            'A',
            'show',
            'Visualizzazione dettagli del sensore',
            ['sensor_id' => $sensor->id]
        );

        return view('sensors.show', compact('sensor'));
    }

    public function edit(Sensor $sensor)
    {
        // Log dell'attività di accesso alla modifica del sensore
        CLAIR::logActivity(
            'A',
            'edit',
            'Accesso alla modifica del sensore',
            ['sensor_id' => $sensor->id]
        );

        return view('sensors.edit', compact('sensor'));
    }

    public function update(Request $request, Sensor $sensor)
    {
        $sensor->update($request->all());

        // Log dell'attività di aggiornamento del sensore
        CLAIR::logActivity(
            'A',
            'update',
            'Aggiornamento dei dettagli del sensore',
            ['sensor_id' => $sensor->id, 'data' => $request->all()]
        );

        return redirect()->route('sensors.index');
    }

    public function destroy(Sensor $sensor)
    {
        $sensor->delete();

        // Log dell'attività di eliminazione del sensore
        CLAIR::logActivity(
            'A',
            'destroy',
            'Eliminazione del sensore',
            ['sensor_id' => $sensor->id]
        );

        return redirect()->route('sensors.index');
    }
}

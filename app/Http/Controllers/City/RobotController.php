<?php

namespace App\Http\Controllers\City;

use App\Models\CLAIR;
use App\Models\City\Robot;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RobotController extends Controller
{
    public function index()
    {
        $robots = Robot::all();

        // Registra l'attività di visualizzazione della lista dei robot
        CLAIR::logActivity(
            'C',
            'index',
            'Visualizzazione dell\'elenco dei robot',
            ['robot_count' => $robots->count()]
        );

        return view('robots.index', compact('robots'));
    }

    public function create()
    {
        // Registra l'attività di apertura della pagina di creazione di un robot
        CLAIR::logActivity(
            'C',
            'create',
            'Apertura della pagina di creazione di un robot',
            []
        );

        return view('robots.create');
    }

    public function store(Request $request)
    {
        $robot = Robot::create($request->all());

        // Registra l'attività di creazione di un nuovo robot
        CLAIR::logActivity(
            'A',
            'store',
            'Creazione di un nuovo robot',
            ['robot_id' => $robot->id]
        );

        return redirect()->route('robots.index');
    }

    public function show(Robot $robot)
    {
        // Registra l'attività di visualizzazione dei dettagli di un robot
        CLAIR::logActivity(
            'R',
            'show',
            'Visualizzazione dei dettagli del robot',
            ['robot_id' => $robot->id]
        );

        return view('robots.show', compact('robot'));
    }

    public function edit(Robot $robot)
    {
        // Registra l'attività di apertura della pagina di modifica di un robot
        CLAIR::logActivity(
            'C',
            'edit',
            'Apertura della pagina di modifica del robot',
            ['robot_id' => $robot->id]
        );

        return view('robots.edit', compact('robot'));
    }

    public function update(Request $request, Robot $robot)
    {
        $robot->update($request->all());

        // Registra l'attività di aggiornamento di un robot
        CLAIR::logActivity(
            'A',
            'update',
            'Aggiornamento dei dettagli del robot',
            ['robot_id' => $robot->id]
        );

        return redirect()->route('robots.index');
    }

    public function destroy(Robot $robot)
    {
        $robotId = $robot->id;
        $robot->delete();

        // Registra l'attività di eliminazione di un robot
        CLAIR::logActivity(
            'R',
            'destroy',
            'Eliminazione di un robot',
            ['robot_id' => $robotId]
        );

        return redirect()->route('robots.index');
    }
}

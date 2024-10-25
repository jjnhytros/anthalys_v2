<?php

namespace App\Http\Controllers\City;

use App\Models\City\Drone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DroneController extends Controller
{
    public function index()
    {
        $drones = Drone::all();
        return view('drones.index', compact('drones'));
    }

    public function create()
    {
        return view('drones.create');
    }

    public function store(Request $request)
    {
        Drone::create($request->all());
        return redirect()->route('drones.index');
    }

    public function show(Drone $drone)
    {
        return view('drones.show', compact('drone'));
    }

    public function edit(Drone $drone)
    {
        return view('drones.edit', compact('drone'));
    }

    public function update(Request $request, Drone $drone)
    {
        $drone->update($request->all());
        return redirect()->route('drones.index');
    }

    public function destroy(Drone $drone)
    {
        $drone->delete();
        return redirect()->route('drones.index');
    }
}

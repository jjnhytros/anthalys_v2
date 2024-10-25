<?php

namespace App\Http\Controllers\Agricolture;

use Illuminate\Http\Request;
use App\Models\Agricolture\Sensor;
use App\Http\Controllers\Controller;

class SensorController extends Controller
{
    public function index()
    {
        $sensors = Sensor::all();
        return view('sensors.index', compact('sensors'));
    }

    public function create()
    {
        return view('sensors.create');
    }

    public function store(Request $request)
    {
        Sensor::create($request->all());
        return redirect()->route('sensors.index');
    }

    public function show(Sensor $sensor)
    {
        return view('sensors.show', compact('sensor'));
    }

    public function edit(Sensor $sensor)
    {
        return view('sensors.edit', compact('sensor'));
    }

    public function update(Request $request, Sensor $sensor)
    {
        $sensor->update($request->all());
        return redirect()->route('sensors.index');
    }

    public function destroy(Sensor $sensor)
    {
        $sensor->delete();
        return redirect()->route('sensors.index');
    }
}

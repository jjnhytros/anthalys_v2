<?php

namespace App\Http\Controllers\Agricolture;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Agricolture\Greenhouse;

class GreenhouseController extends Controller
{
    public function index()
    {
        $greenhouses = Greenhouse::all();
        return view('greenhouses.index', compact('greenhouses'));
    }

    public function create()
    {
        return view('greenhouses.create');
    }

    public function store(Request $request)
    {
        Greenhouse::create($request->all());
        return redirect()->route('greenhouses.index');
    }

    public function show(Greenhouse $greenhouse)
    {
        return view('greenhouses.show', compact('greenhouse'));
    }

    public function edit(Greenhouse $greenhouse)
    {
        return view('greenhouses.edit', compact('greenhouse'));
    }

    public function update(Request $request, Greenhouse $greenhouse)
    {
        $greenhouse->update($request->all());
        return redirect()->route('greenhouses.index');
    }

    public function destroy(Greenhouse $greenhouse)
    {
        $greenhouse->delete();
        return redirect()->route('greenhouses.index');
    }
}

<?php

namespace App\Http\Controllers\Agricolture;

use Illuminate\Http\Request;
use App\Models\Agricolture\Farm;
use App\Http\Controllers\Controller;

class FarmController extends Controller
{
    public function index()
    {
        $farms = Farm::all();
        return view('farms.index', compact('farms'));
    }

    public function create()
    {
        return view('farms.create');
    }

    public function store(Request $request)
    {
        Farm::create($request->all());
        return redirect()->route('farms.index');
    }

    public function show(Farm $farm)
    {
        return view('farms.show', compact('farm'));
    }

    public function edit(Farm $farm)
    {
        return view('farms.edit', compact('farm'));
    }

    public function update(Request $request, Farm $farm)
    {
        $farm->update($request->all());
        return redirect()->route('farms.index');
    }

    public function destroy(Farm $farm)
    {
        $farm->delete();
        return redirect()->route('farms.index');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function index(District $district)
    {
        $buildings = $district->buildings;
        return view('buildings.index', compact('district', 'buildings'));
    }

    public function create(District $district)
    {
        return view('buildings.create', compact('district'));
    }

    public function store(Request $request, District $district)
    {
        $district->buildings()->create($request->all());
        return redirect()->route('districts.buildings.index', $district);
    }
}

<?php

namespace App\Http\Controllers\City;

use Illuminate\Http\Request;
use App\Models\City\District;
use App\Http\Controllers\Controller;

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

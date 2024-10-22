<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        // Otteniamo la città Anthalys e carichiamo i distretti con edifici e infrastrutture
        $city = City::with(['districts.buildings', 'districts.infrastructures', 'districts.resources'])->first();

        // Riepilogo del consumo totale delle risorse per la città
        $totalEnergyConsumption = $city->districts->sum(function ($district) {
            return $district->buildings->sum('energy_consumption');
        });
        $totalWaterConsumption = $city->districts->sum(function ($district) {
            return $district->buildings->sum('water_consumption');
        });
        $totalFoodConsumption = $city->districts->sum(function ($district) {
            return $district->buildings->sum('food_consumption');
        });

        return view('home', compact('city', 'totalEnergyConsumption', 'totalWaterConsumption', 'totalFoodConsumption'));
    }


    public function create()
    {
        return view('cities.create');
    }

    public function store(Request $request)
    {
        City::create($request->all());
        return redirect()->route('cities.index');
    }

    public function show()
    {
        //
    }
}

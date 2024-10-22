<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index()
    {
        $city = City::first();
        $districts = $city->districts;
        return view('districts.index', compact('city', 'districts'));
    }


    public function create(City $city)
    {
        return view('districts.create', compact('city'));
    }

    public function store(Request $request, City $city)
    {
        // Dati delle risorse attuali della città (Esempio)
        $availableResources = [
            'Energia' => 100000, // Esempio di risorse disponibili
            'Acqua' => 50000,
            'Cibo' => 30000,
        ];

        // Simuliamo che nel form c'è un campo `resource_requirement` che contiene le risorse richieste
        $requiredResources = json_decode($request->resource_requirement, true); // Decodifica in array

        // Verifica delle risorse
        foreach ($requiredResources as $resourceName => $requiredAmount) {
            if ($requiredAmount > $availableResources[$resourceName]) {
                return back()->withErrors(['Risorse insufficienti per creare il distretto.']);
            }
        }

        // Se le risorse sono sufficienti, creiamo il nuovo distretto
        $city->districts()->create($request->all());

        return redirect()->route('districts.index', $city)->with('success', 'Distretto creato con successo!');
    }

    public function resources(District $district)
    {
        $resources = $district->resources;
        return view('resources.index', compact('district', 'resources'));
    }

    public function monitorResources(District $district)
    {
        $buildings = $district->buildings;
        $infrastructures = $district->infrastructures;

        $totalEnergyConsumption = $buildings->sum('energy_consumption');
        $totalWaterConsumption = $buildings->sum('water_consumption');
        $totalFoodConsumption = $buildings->sum('food_consumption');

        $energyDistributed = 0;
        $waterDistributed = 0;
        $foodDistributed = 0;

        foreach ($infrastructures as $infrastructure) {
            if ($infrastructure->type == 'Rete Elettrica') {
                $energyDistributed += $infrastructure->calculateDistributedResource($totalEnergyConsumption);
            } elseif ($infrastructure->type == 'Rete Idrica') {
                $waterDistributed += $infrastructure->calculateDistributedResource($totalWaterConsumption);
            } elseif ($infrastructure->type == 'Rete Fognaria') {
                $foodDistributed += $infrastructure->calculateDistributedResource($totalFoodConsumption);
            }
        }

        return view('districts.resource_monitor', compact(
            'district',
            'totalEnergyConsumption',
            'totalWaterConsumption',
            'totalFoodConsumption',
            'energyDistributed',
            'waterDistributed',
            'foodDistributed'
        ));
    }
}

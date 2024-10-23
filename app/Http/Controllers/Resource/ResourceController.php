<?php

namespace App\Http\Controllers\Resource;

use App\Models\City\City;
use Illuminate\Http\Request;
use App\Models\City\District;
use App\Models\Resource\Resource;
use App\Http\Controllers\Controller;

class ResourceController extends Controller
{
    public function index()
    {
        // Recupera tutte le risorse da tutti i distretti
        $resources = Resource::all();

        // Aggregare i dati per ciascuna risorsa
        $aggregatedResources = [
            'Energia' => [
                'total_quantity' => $resources->where('name', 'Energia')->sum('quantity'),
                'total_produced' => $resources->where('name', 'Energia')->sum('produced'),
                'total_consumed' => $resources->where('name', 'Energia')->sum('consumed'),
            ],
            'Acqua' => [
                'total_quantity' => $resources->where('name', 'Acqua')->sum('quantity'),
                'total_produced' => $resources->where('name', 'Acqua')->sum('produced'),
                'total_consumed' => $resources->where('name', 'Acqua')->sum('consumed'),
            ],
            'Cibo' => [
                'total_quantity' => $resources->where('name', 'Cibo')->sum('quantity'),
                'total_produced' => $resources->where('name', 'Cibo')->sum('produced'),
                'total_consumed' => $resources->where('name', 'Cibo')->sum('consumed'),
            ],
        ];

        // Identifica distretti in surplus o deficit di risorse
        $districts = District::with('resources')->get();
        $districtAnalysis = $districts->map(function ($district) {
            $surplusOrDeficit = $district->resources->map(function ($resource) {
                return [
                    'name' => $resource->name,
                    'surplus_or_deficit' => $resource->produced - $resource->consumed,
                ];
            });
            return [
                'district_name' => $district->name,
                'resources' => $surplusOrDeficit,
            ];
        });

        // Passare i dati alla view
        return view('resources.analysis', compact('aggregatedResources', 'districtAnalysis'));
    }


    public function create(District $district)
    {
        return view('resources.create', compact('district'));
    }

    public function store(Request $request, District $district)
    {
        $district->resources()->create($request->all());
        return redirect()->route('districts.resources.index', $district);
    }

    public function transfer()
    {
        $districts = District::all();
        return view('resources.transfer', compact('districts'));
    }

    public function getResources(District $district)
    {
        $resources = $district->resources;
        return response()->json($resources);
    }
    public function transferView()
    {
        $districts = District::all();
        return view('resources.transfer', compact('districts'));
    }

    public function monitorCityResources()
    {
        $city = City::with('districts.resources')->first();

        // Calcola il consumo e la produzione totale delle risorse
        $totalEnergyConsumption = $city->districts->sum(function ($district) {
            return $district->resources->where('name', 'Energia')->sum('consumed');
        });

        $totalWaterConsumption = $city->districts->sum(function ($district) {
            return $district->resources->where('name', 'Acqua')->sum('consumed');
        });

        $totalMaterialsRecycled = $city->districts->sum(function ($district) {
            return $district->resources->where('name', 'Materiali')->sum('produced');
        });

        // Impatto del riciclo
        $energySaved = $city->energy_saved;
        $waterSaved = $city->water_saved;
        $materialsSaved = $city->materials_saved;

        return view('resources.monitor', compact('totalEnergyConsumption', 'totalWaterConsumption', 'totalMaterialsRecycled', 'energySaved', 'waterSaved', 'materialsSaved'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Resource;
use Illuminate\Http\Request;

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
}

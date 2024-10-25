<?php

namespace App\Http\Controllers\Resource;

use App\Models\City\City;
use App\Models\City\Message;
use Illuminate\Http\Request;
use App\Models\City\District;
use App\Models\Resource\Resource;
use App\Http\Controllers\Controller;
use App\Models\Resource\ResourceTransfer;

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

    public function checkResourceNeeds(District $district)
    {
        foreach ($district->resources as $resource) {
            if ($resource->available_quantity < $resource->minimum_required) {
                $this->requestResourceTransfer($district, $resource);
            }
        }
    }

    public function monitorResourceConsumption(District $district)
    {
        foreach ($district->resources as $resource) {
            $resource->available_quantity -= $resource->daily_consumption;
            $resource->save();

            if ($resource->available_quantity < $resource->minimum_required) {
                $this->requestResourceTransfer($district, $resource);
            }
        }
    }

    public function requestResourceTransfer(District $district, $resource)
    {
        // Trova un distretto con surplus della risorsa
        $surplus_district = District::whereHas('resources', function ($query) use ($resource) {
            $query->where('name', $resource->name)->where('available_quantity', '>', $resource->minimum_required);
        })->first();

        if ($surplus_district) {
            // Pianifica il trasferimento utilizzando i nomi dei campi corretti
            ResourceTransfer::create([
                'source_district_id' => $surplus_district->id,
                'target_district_id' => $district->id,
                'resource_id' => $resource->id,
                'quantity' => $resource->minimum_required - $resource->available_quantity,
            ]);

            // Aggiorna le quantità di risorse nei distretti
            $surplus_district->resources()->decrement('available_quantity', $resource->minimum_required - $resource->available_quantity);
            $district->resources()->increment('available_quantity', $resource->minimum_required - $resource->available_quantity);

            // Invia una notifica al distretto
            Message::create([
                'sender_id' => 2, // ID del governo
                'recipient_id' => $district->manager_id, // Assumendo che ogni distretto abbia un manager
                'subject' => 'Trasferimento Risorse',
                'body' => "Il trasferimento di {$resource->name} è stato completato da {$surplus_district->name} a {$district->name}.",
                'is_read' => false,
                'is_archived' => false,
                'is_notification' => true,
            ]);
        }
    }
}

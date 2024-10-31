<?php

namespace App\Http\Controllers\Resource;

use App\Models\CLAIR;
use App\Models\City\City;
use App\Models\City\Message;
use App\Models\City\District;
use App\Models\Resource\Resource;
use App\Http\Controllers\Controller;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::all();

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

        // Log dell'attività di visualizzazione delle risorse
        CLAIR::logActivity('C', 'index', 'Visualizzazione delle risorse e analisi dei distretti', [
            'aggregated_resources' => $aggregatedResources,
            'districts_analyzed' => $districts->count(),
        ]);

        return view('resources.analysis', compact('aggregatedResources', 'districtAnalysis'));
    }

    public function monitorCityResources()
    {
        $city = City::with('districts.resources')->first();

        $totalEnergyConsumption = $city->districts->sum(function ($district) {
            return $district->resources->where('name', 'Energia')->sum('consumed');
        });
        $totalWaterConsumption = $city->districts->sum(function ($district) {
            return $district->resources->where('name', 'Acqua')->sum('consumed');
        });
        $totalMaterialsRecycled = $city->districts->sum(function ($district) {
            return $district->resources->where('name', 'Materiali')->sum('produced');
        });

        $energySaved = $city->energy_saved;
        $waterSaved = $city->water_saved;
        $materialsSaved = $city->materials_saved;

        // Log attività di monitoraggio delle risorse della città
        CLAIR::logActivity('A', 'monitorCityResources', 'Monitoraggio risorse della città', [
            'total_energy_consumption' => $totalEnergyConsumption,
            'total_water_consumption' => $totalWaterConsumption,
            'total_materials_recycled' => $totalMaterialsRecycled,
        ]);

        return view('resources.monitor', compact(
            'totalEnergyConsumption',
            'totalWaterConsumption',
            'totalMaterialsRecycled',
            'energySaved',
            'waterSaved',
            'materialsSaved'
        ));
    }

    public function sendResourceAlerts()
    {
        $resources = Resource::all();
        $governmentId = 2;

        foreach ($resources as $resource) {
            if ($resource->availability < 50 || abs($resource->price - $resource->previous_price) / $resource->previous_price > 0.2) {
                Message::create([
                    'sender_id' => null,
                    'recipient_id' => $governmentId,
                    'subject' => 'Avviso Risorsa Critica',
                    'message' => "La risorsa {$resource->name} ha raggiunto una disponibilità critica o ha subito una variazione significativa del prezzo.",
                    'type' => 'notification',
                    'url' => route('resources.show', $resource->id),
                    'is_notification' => true,
                    'status' => 'unread'
                ]);

                // Log dell'attività di invio avvisi di risorse critiche
                CLAIR::logActivity('I', 'sendResourceAlerts', 'Avvisi di risorse critiche inviati', [
                    'resource_name' => $resource->name,
                    'availability' => $resource->availability,
                    'price_variation' => abs($resource->price - $resource->previous_price) / $resource->previous_price,
                ]);
            }
        }
    }
}

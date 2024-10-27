<?php

namespace App\Http\Controllers\City;

use App\Models\CLAIR;
use App\Models\City\City;
use App\Models\City\Citizen;
use App\Models\City\Message;
use Illuminate\Http\Request;
use App\Models\City\District;
use App\Models\City\Migration;
use App\Jobs\TransferResourcesJob;
use App\Http\Controllers\Controller;

class DistrictController extends Controller
{
    public function index()
    {
        $city = City::first();
        $districts = $city->districts;

        // Identifica i distretti con problemi di risorse o infrastrutture
        $problematicDistricts = District::whereHas('resources', function ($query) {
            $query->where('quantity', '<', 500);
        })->orWhereHas('infrastructures', function ($query) {
            $query->where('condition', '<', 0.5);
        })->get();

        // Recupera le migrazioni con i dati dei distretti di partenza e destinazione
        $migrations = Migration::with('fromDistrict', 'toDistrict')->get();

        // Registra l'attività di visualizzazione dei distretti e dei dati sulle migrazioni
        CLAIR::logActivity(
            'C',
            'index',
            'Visualizzazione dei distretti, migrazioni e distretti problematici',
            [
                'total_districts' => $districts->count(),
                'total_migrations' => $migrations->count(),
                'problematic_districts_count' => $problematicDistricts->count()
            ]
        );

        return view('districts.index', compact('city', 'districts', 'migrations', 'problematicDistricts'));
    }

    public function create(City $city)
    {
        return view('districts.create', compact('city'));
    }

    public function store(Request $request, City $city)
    {
        $availableResources = [
            'Energia' => 100000,
            'Acqua' => 50000,
            'Cibo' => 30000,
        ];

        $requiredResources = json_decode($request->resource_requirement, true);

        foreach ($requiredResources as $resourceName => $requiredAmount) {
            if ($requiredAmount > $availableResources[$resourceName]) {
                return back()->withErrors(['Risorse insufficienti per creare il distretto.']);
            }
        }

        $newDistrict = $city->districts()->create($request->all());

        // Log della creazione del distretto
        CLAIR::logActivity(
            'A',
            'store',
            'Creazione di un nuovo distretto',
            ['city_id' => $city->id, 'district_id' => $newDistrict->id]
        );

        return redirect()->route('districts.index', $city)->with('success', 'Distretto creato con successo!');
    }

    public function monitorResources(District $district)
    {
        $buildings = $district->buildings;
        $infrastructures = $district->infrastructures;

        $totalEnergyConsumption = $buildings->sum('energy_consumption');
        $totalWaterConsumption = $buildings->sum('water_consumption');
        $totalFoodConsumption = $buildings->sum('food_consumption');

        $energyDistributed = $waterDistributed = $foodDistributed = 0;

        foreach ($infrastructures as $infrastructure) {
            if ($infrastructure->type == 'Rete Elettrica') {
                $energyDistributed += $infrastructure->calculateDistributedResource($totalEnergyConsumption);
            } elseif ($infrastructure->type == 'Rete Idrica') {
                $waterDistributed += $infrastructure->calculateDistributedResource($totalWaterConsumption);
            } elseif ($infrastructure->type == 'Rete Fognaria') {
                $foodDistributed += $infrastructure->calculateDistributedResource($totalFoodConsumption);
            }
        }

        // Log monitoraggio delle risorse del distretto
        CLAIR::logActivity(
            'I',
            'monitorResources',
            'Monitoraggio delle risorse del distretto',
            [
                'district_id' => $district->id,
                'total_energy' => $totalEnergyConsumption,
                'total_water' => $totalWaterConsumption,
                'total_food' => $totalFoodConsumption,
            ]
        );

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

    public function transferResources(Request $request)
    {
        $fromDistrict = District::findOrFail($request->input('from_district_id'));
        $toDistrict = District::findOrFail($request->input('to_district_id'));
        $resourceType = $request->input('resource_type');
        $amount = $request->input('amount');

        TransferResourcesJob::dispatch($fromDistrict, $toDistrict, $resourceType, $amount);

        // Log del trasferimento di risorse
        CLAIR::logActivity(
            'A',
            'transferResources',
            'Trasferimento di risorse tra distretti',
            [
                'from_district_id' => $fromDistrict->id,
                'to_district_id' => $toDistrict->id,
                'resource_type' => $resourceType,
                'amount' => $amount
            ]
        );

        return redirect()->route('districts.resources')->with('success', 'Risorse trasferite con successo.');
    }

    protected function sendResourceAlert($resourceType, District $district)
    {
        $government = Citizen::find(2);

        Message::create([
            'sender_id' => null,
            'recipient_id' => $government->id,
            'subject' => 'Allerta Risorse: Basso Livello di ' . ucfirst($resourceType),
            'message' => 'Il distretto "' . $district->name . '" ha un livello critico di ' . $resourceType . '.',
            'type' => 'alert',
            'url' => route('districts.show', $district->id),
            'is_message' => false,
            'is_notification' => true,
            'is_email' => false,
            'status' => 'unread',
        ]);

        // Log dell'invio dell'allerta di risorse
        CLAIR::logActivity(
            'R',
            'sendResourceAlert',
            'Invio di allerta per risorse critiche',
            [
                'district_id' => $district->id,
                'resource_type' => $resourceType
            ]
        );
    }
}

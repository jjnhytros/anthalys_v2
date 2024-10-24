<?php

namespace App\Http\Controllers\City;

use App\Models\City\City;
use App\Models\City\Citizen;
use App\Models\City\Message;
use Illuminate\Http\Request;
use App\Models\City\District;
use App\Jobs\TransferResourcesJob;
use App\Http\Controllers\Controller;

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

    public function showRecyclingProgress(District $district)
    {
        // Ottieni tutti gli obiettivi di riciclo per il distretto
        $recyclingGoals = $district->recyclingGoals;

        return view('districts.recycling_progress', compact('district', 'recyclingGoals'));
    }

    public function showEnvironmentalImpact(District $district)
    {
        // Sommiamo le emissioni, i consumi energetici, idrici e l'impatto sulla biodiversità
        $totalCO2Emissions = $district->infrastructures->sum('co2_emissions');
        $totalEnergyConsumption = $district->infrastructures->sum('energy_consumption');
        $totalWaterConsumption = $district->infrastructures->sum('water_consumption');
        $totalBiodiversityImpact = $district->infrastructures->avg('biodiversity_impact'); // Media impatto biodiversità

        return view('districts.environmental_impact', compact(
            'district',
            'totalCO2Emissions',
            'totalEnergyConsumption',
            'totalWaterConsumption',
            'totalBiodiversityImpact'
        ));
    }

    public function showResources()
    {
        $districts = District::all();
        return view('districts.resources', compact('districts'));
    }

    public function showTransferForm($id)
    {
        $district = District::findOrFail($id);
        $otherDistricts = District::where('id', '!=', $district->id)->get();
        return view('districts.transfer', compact('district', 'otherDistricts'));
    }

    public function transferResources(Request $request)
    {
        $fromDistrict = District::findOrFail($request->input('from_district_id'));
        $toDistrict = District::findOrFail($request->input('to_district_id'));
        $resourceType = $request->input('resource_type');
        $amount = $request->input('amount');

        // Esegui il job per trasferire le risorse
        TransferResourcesJob::dispatch($fromDistrict, $toDistrict, $resourceType, $amount);

        return redirect()->route('districts.resources')->with('success', 'Risorse trasferite con successo.');
    }

    public function showDashboard()
    {
        // Recuperiamo i distretti con risorse critiche
        $criticalDistricts = District::where('energy', '<', 'energy_threshold')
            ->orWhere('water', '<', 'water_threshold')
            ->orWhere('food', '<', 'food_threshold')
            ->get();

        // Invia le notifiche quando necessario
        foreach ($criticalDistricts as $district) {
            if ($district->energy < $district->energy_threshold) {
                $this->sendResourceAlert('energia', $district);  // Passiamo il distretto come parametro
            }
            if ($district->water < $district->water_threshold) {
                $this->sendResourceAlert('acqua', $district);  // Passiamo il distretto come parametro
            }
            if ($district->food < $district->food_threshold) {
                $this->sendResourceAlert('cibo', $district);  // Passiamo il distretto come parametro
            }
        }

        return view('dashboard', compact('criticalDistricts'));
    }

    protected function sendResourceAlert($resourceType, District $district)
    {
        // Recupera gli amministratori per inviare le notifiche
        $govenment = Citizen::find(2);

        Message::create([
            'sender_id' => null, // Notifica di sistema
            'recipient_id' => $govenment->id, // Destinatario
            'subject' => 'Allerta Risorse: Basso Livello di ' . ucfirst($resourceType),
            'message' => 'Il distretto "' . $district->name . '" ha un livello critico di ' . $resourceType . '.',
            'type' => 'alert',
            'url' => route('districts.show', $district->id),
            'is_message' => false, // Non è un messaggio
            'is_notification' => true, // È una notifica
            'is_email' => false, // Non è un'email
            'status' => 'unread', // Notifica non letta
        ]);
    }
}

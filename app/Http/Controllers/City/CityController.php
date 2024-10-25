<?php

namespace App\Http\Controllers\City;

use App\Models\User;
use App\Models\City\City;
use Illuminate\Http\Request;
use App\Models\City\District;
use App\Models\City\Transaction;
use App\Models\Resource\Resource;
use App\Http\Controllers\Controller;
use App\Models\Resource\ResourceTransfer;

class CityController extends Controller
{
    public function index()
    {
        // Otteniamo la città Anthalys e carichiamo i distretti con edifici e infrastrutture
        $city = City::with(['districts.buildings', 'districts.infrastructures', 'districts.resources'])->first();

        // Riepilogo del consumo totale delle risorse per la città
        $city = City::with([
            'districts.buildings' => function ($query) {
                $query->selectRaw('sum(energy_consumption) as totalEnergy, sum(water_consumption) as totalWater, sum(food_consumption) as totalFood');
            },
        ])->first();
        $totalWaterConsumption = $city->districts->sum(function ($district) {
            return $district->buildings->sum('water_consumption');
        });
        $totalFoodConsumption = $city->districts->sum(function ($district) {
            return $district->buildings->sum('food_consumption');
        });
        // Recupera il bilancio del governo
        $government = User::where('name', 'government')->first();

        // Recupera le transazioni del governo (entrate e spese)
        $transactions = Transaction::orderBy('created_at', 'desc')->get();

        return view('home', compact('city', 'totalEnergyConsumption', 'totalWaterConsumption', 'totalFoodConsumption', 'government', 'transactions'));
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

    public function increaseResourceProduction(City $city)
    {
        // Cicliamo attraverso i distretti della città
        foreach ($city->districts as $district) {
            // Cicliamo attraverso le risorse del distretto
            foreach ($district->resources as $resource) {
                // Definisci una logica per aumentare la produzione, ad esempio aumentiamo del 5%
                $incrementFactor = 0.05;
                $resource->daily_production += $resource->daily_production * $incrementFactor;
                $resource->save();
            }
        }

        return back()->with('success', 'Produzione delle risorse incrementata con successo!');
    }
    public function monitorResources()
    {
        $districts = District::with('resources')->get();
        $surplusDistricts = [];
        $deficitDistricts = [];

        foreach ($districts as $district) {
            foreach ($district->resources as $resource) {
                $netProduction = $resource->daily_production - $resource->consumed;
                if ($netProduction > 0) {
                    // Il distretto ha un surplus di risorse
                    $surplusDistricts[$district->id][$resource->name] = $netProduction;
                } elseif ($netProduction < 0) {
                    // Il distretto ha un deficit di risorse
                    $deficitDistricts[$district->id][$resource->name] = abs($netProduction);
                }
            }
        }

        return [$surplusDistricts, $deficitDistricts];
    }
    public function autoTransferResources()
    {
        [$surplusDistricts, $deficitDistricts] = $this->monitorResources();

        // Ordiniamo i distretti in base al deficit e alla priorità della risorsa
        foreach ($deficitDistricts as $deficitDistrictId => $deficitResources) {
            // Ordina le risorse per priorità
            arsort($deficitResources); // Ordina in base ai valori delle risorse

            foreach ($deficitResources as $resourceName => $deficitAmount) {
                // Trova un distretto con surplus della stessa risorsa
                foreach ($surplusDistricts as $surplusDistrictId => $surplusResources) {
                    if (isset($surplusResources[$resourceName]) && $surplusResources[$resourceName] > 0) {
                        $transferAmount = min($surplusResources[$resourceName], $deficitAmount);

                        // Esegui il trasferimento
                        $this->transferResourceBetweenDistricts($surplusDistrictId, $deficitDistrictId, $resourceName, $transferAmount);

                        // Aggiorna il surplus e il deficit
                        $surplusResources[$resourceName] -= $transferAmount;
                        $deficitAmount -= $transferAmount;

                        // Se il deficit è colmato, passa alla risorsa successiva
                        if ($deficitAmount <= 0) {
                            break;
                        }
                    }
                }
            }
        }

        return back()->with('success', 'Trasferimenti automatici di risorse completati con successo!');
    }
    public function getGovernmentBalance()
    {
        $government = User::where('name', 'government')->first();

        if ($government) {
            return response()->json(['balance' => $government->cash]);
        }

        return response()->json(['error' => 'Government not found'], 404);
    }

    protected function transferResourceBetweenDistricts($sourceDistrictId, $targetDistrictId, $resourceName, $quantity)
    {
        $sourceResource = Resource::where('district_id', $sourceDistrictId)->where('name', $resourceName)->first();
        $targetResource = Resource::firstOrCreate([
            'district_id' => $targetDistrictId,
            'name' => $resourceName,
        ], [
            'quantity' => 0,
            'daily_production' => 0,
            'consumed' => 0,
            'unit' => $sourceResource->unit,
        ]);

        // Aggiorna le quantità nei distretti
        $sourceResource->quantity -= $quantity;
        $sourceResource->save();

        $targetResource->quantity += $quantity;
        $targetResource->save();

        // Registra il trasferimento
        ResourceTransfer::create([
            'source_district_id' => $sourceDistrictId,
            'target_district_id' => $targetDistrictId,
            'resource_id' => $sourceResource->id,
            'quantity' => $quantity,
        ]);
    }
    protected function getResourcePriority($resourceName)
    {
        // Definiamo la priorità per ogni tipo di risorsa
        $priorities = [
            'Energia' => 1, // Alta priorità
            'Acqua' => 2,   // Media priorità
            'Cibo' => 3,    // Bassa priorità
        ];

        // Restituiamo la priorità della risorsa (default 4 per eventuali risorse sconosciute)
        return $priorities[$resourceName] ?? 4;
    }
    protected function getDistrictPriority($district)
    {
        // Definiamo la priorità per ogni tipo di distretto (supponiamo che ci sia un campo 'type' nel distretto)
        $priorities = [
            'Industriale' => 1,  // Alta priorità
            'Residenziale' => 2, // Media priorità
            'Commerciale' => 3,  // Bassa priorità
        ];

        return $priorities[$district->type] ?? 4; // Default se non è definito
    }
}

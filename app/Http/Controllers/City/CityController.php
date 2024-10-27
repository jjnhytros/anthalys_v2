<?php

namespace App\Http\Controllers\City;

use App\Models\User;
use App\Models\CLAIR;
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

        $totalWaterConsumption = $city->districts->sum(fn($district) => $district->buildings->sum('water_consumption'));
        $totalFoodConsumption = $city->districts->sum(fn($district) => $district->buildings->sum('food_consumption'));

        $government = User::where('name', 'government')->first();
        $transactions = Transaction::orderBy('created_at', 'desc')->get();

        CLAIR::logActivity('C', 'index', 'Caricamento iniziale della città e riepilogo delle risorse', compact('city'));

        return view('home', compact('city', 'totalEnergyConsumption', 'totalWaterConsumption', 'totalFoodConsumption', 'government', 'transactions'));
    }

    public function increaseResourceProduction(City $city)
    {
        foreach ($city->districts as $district) {
            foreach ($district->resources as $resource) {
                $incrementFactor = 0.05;
                $resource->daily_production += $resource->daily_production * $incrementFactor;
                $resource->save();
            }
        }

        CLAIR::logActivity('A', 'increaseResourceProduction', 'Incremento della produzione di risorse del 5% in ogni distretto della città', ['city_id' => $city->id]);

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
                    $surplusDistricts[$district->id][$resource->name] = $netProduction;
                } elseif ($netProduction < 0) {
                    $deficitDistricts[$district->id][$resource->name] = abs($netProduction);
                }
            }
        }

        CLAIR::logActivity('R', 'monitorResources', 'Monitoraggio delle risorse per surplus e deficit nei distretti', compact('surplusDistricts', 'deficitDistricts'));

        return [$surplusDistricts, $deficitDistricts];
    }

    public function autoTransferResources()
    {
        [$surplusDistricts, $deficitDistricts] = $this->monitorResources();

        foreach ($deficitDistricts as $deficitDistrictId => $deficitResources) {
            arsort($deficitResources);

            foreach ($deficitResources as $resourceName => $deficitAmount) {
                foreach ($surplusDistricts as $surplusDistrictId => $surplusResources) {
                    if (isset($surplusResources[$resourceName]) && $surplusResources[$resourceName] > 0) {
                        $transferAmount = min($surplusResources[$resourceName], $deficitAmount);
                        $this->transferResourceBetweenDistricts($surplusDistrictId, $deficitDistrictId, $resourceName, $transferAmount);

                        $surplusResources[$resourceName] -= $transferAmount;
                        $deficitAmount -= $transferAmount;

                        if ($deficitAmount <= 0) {
                            break;
                        }
                    }
                }
            }
        }

        CLAIR::logActivity('I', 'autoTransferResources', 'Trasferimento automatico di risorse tra distretti per bilanciare surplus e deficit', compact('surplusDistricts', 'deficitDistricts'));

        return back()->with('success', 'Trasferimenti automatici di risorse completati con successo!');
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

        $sourceResource->quantity -= $quantity;
        $sourceResource->save();

        $targetResource->quantity += $quantity;
        $targetResource->save();

        ResourceTransfer::create([
            'source_district_id' => $sourceDistrictId,
            'target_district_id' => $targetDistrictId,
            'resource_id' => $sourceResource->id,
            'quantity' => $quantity,
        ]);

        CLAIR::logActivity('I', 'transferResourceBetweenDistricts', 'Trasferimento di risorse tra distretti', [
            'source_district_id' => $sourceDistrictId,
            'target_district_id' => $targetDistrictId,
            'resource_name' => $resourceName,
            'quantity' => $quantity,
        ]);
    }
}

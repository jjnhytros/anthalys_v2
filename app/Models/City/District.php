<?php

namespace App\Models\City;

use App\Models\Resource\Resource;
use Illuminate\Database\Eloquent\Model;
use App\Models\Recycling\DistrictRecyclingGoal;

class District extends Model
{
    // Attributi riempibili
    protected $fillable = [
        'name',
        'population',
        'type',
        'area',
        'description',
        'soil_health',
        'city_id',
        'infrastructure_efficiency',
        'technology_level'
    ];

    // Relazioni
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function manager()
    {
        return $this->belongsTo(Citizen::class, 'manager_id');
    }

    public function buildings()
    {
        return $this->hasMany(Building::class);
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }

    public function citizens()
    {
        return $this->hasManyThrough(Citizen::class, Building::class, 'district_id', 'residential_building_id');
    }

    public function infrastructures()
    {
        return $this->hasMany(Infrastructure::class);
    }

    public function recyclingGoals()
    {
        return $this->hasMany(DistrictRecyclingGoal::class);
    }

    public function migrationsFrom()
    {
        return $this->hasMany(Migration::class, 'from_district_id');
    }

    public function migrationsTo()
    {
        return $this->hasMany(Migration::class, 'to_district_id');
    }

    // Funzioni di Trasferimento, Produzione e Vendita delle Risorse
    public function transferResourceTo(District $destination, string $resourceName, float $amount): bool
    {
        $efficiency = $this->infrastructures()->avg('efficiency') ?? 1.00;
        $transferAmount = $amount * $efficiency;
        $resource = $this->resources()->where('name', $resourceName)->orderBy('priority', 'asc')->first();

        if (!$resource || $resource->quantity < $transferAmount) {
            return false;
        }

        $resource->quantity -= $transferAmount;
        $resource->save();

        $destinationResource = $destination->resources()->where('name', $resourceName)->first();
        if (!$destinationResource) {
            $destination->resources()->create(['name' => $resourceName, 'quantity' => $amount]);
        } else {
            $destinationResource->quantity += $amount;
            $destinationResource->save();
        }

        $this->sendTransferNotification($destination, $resourceName, $amount);
        return true;
    }

    public function calculateProductionEfficiency()
    {
        $efficiency = $this->infrastructure_efficiency * $this->technology_level;
        return max(min($efficiency, 2.0), 0.5);
    }

    public function updateResourceProduction()
    {
        $resources = $this->resources;
        foreach ($resources as $resource) {
            $infrastructureLevel = $this->calculateInfrastructureEfficiency();
            $technologyLevel = $this->calculateTechnologyEfficiency();
            $newProduction = $resource->base_production * ($infrastructureLevel + $technologyLevel);

            $resource->update(['production' => $newProduction]);
            $this->sendEfficiencyNotification($resource, $newProduction);
        }
    }

    public function sellExcessResource(string $resourceName, float $excessAmount): bool
    {
        $resource = $this->resources()->where('name', $resourceName)->first();
        if (!$resource || $resource->quantity < $excessAmount) {
            return false;
        }

        $pricePerUnit = $this->getResourceSalePrice($resourceName);
        $totalProfit = $excessAmount * $pricePerUnit;

        $resource->quantity -= $excessAmount;
        $resource->save();

        $government = Citizen::find(2)->user;
        $government->cash += $totalProfit;
        $government->save();

        $this->sendSaleNotification($resourceName, $excessAmount, $totalProfit);
        return true;
    }

    public function getResourceSalePrice(string $resourceName): float
    {
        $prices = [
            'acqua' => 1.5,
            'energia' => 2.0,
            'cibo' => 3.0,
        ];

        return $prices[$resourceName] ?? 1.0;
    }

    // Calcoli di Impatto per Tipologia di Edificio
    public function calculateDistrictEducationImpact()
    {
        return $this->buildings->where('type', 'scuola')->sum->calculateLiteracyImpact();
    }

    public function calculateDistrictHealthImpact()
    {
        return $this->buildings->where('type', 'ospedale')->sum->calculateHealthImpact();
    }

    public function calculateDistrictAdministrativeImpact()
    {
        return $this->buildings->where('type', 'governativo')->sum->calculateAdministrativeImpact();
    }

    public function calculateDistrictFiscalContribution()
    {
        return $this->buildings->where('type', 'governativo')->sum->calculateFiscalContribution();
    }

    public function calculateDistrictCulturalImpact()
    {
        return $this->buildings->where('type', 'culturale')->sum->calculateCulturalImpact();
    }

    public function calculateDistrictEventIncome()
    {
        return $this->buildings->where('type', 'culturale')->sum->calculateEventIncome();
    }

    public function calculateDistrictTransportImpact()
    {
        return $this->buildings->where('type', 'trasporti')->sum->calculateTransportImpact();
    }

    public function calculateDistrictEnergyProduction()
    {
        return $this->buildings->where('type', 'energia')->sum->calculateEnergyProduction();
    }

    public function calculateDistrictWellbeingImpact()
    {
        return $this->buildings->where('type', 'ricreativo')->sum->calculateWellbeingImpact();
    }

    public function calculateDistrictTechnologyImpact()
    {
        return $this->buildings->where('type', 'comunicazione')->sum->calculateTechnologyImpact();
    }

    public function calculateDistrictRecyclingImpact()
    {
        return $this->buildings->where('type', 'gestione_rifiuti')->sum->calculateRecyclingImpact();
    }

    public function calculateDistrictSafetyImpact()
    {
        return $this->buildings->where('type', 'emergenza')->sum->calculateSafetyImpact();
    }

    public function calculateDistrictInnovationImpact()
    {
        return $this->buildings->where('type', 'ricerca')->sum->calculateInnovationImpact();
    }

    // Funzioni Notifica
    private function calculateInfrastructureEfficiency()
    {
        return $this->infrastructure_level ?? 0.7;
    }

    public function sendEfficiencyNotification($resource, $newProduction)
    {
        $government = Citizen::find(2)->user;
        $government->sendNotification(
            'Miglioramento della Produzione',
            'La produzione della risorsa ' . $resource->name . ' è stata ottimizzata a ' . athel($newProduction),
            [
                'url' => url('/resources/' . $resource->id),
                'type' => 'success',
            ]
        );
    }

    private function sendTransferNotification(District $destination, string $resourceName, float $amount)
    {
        $government = Citizen::find(2)->user;
        $government->sendNotification(
            'Trasferimento Risorse',
            'È stato trasferito ' . $amount . ' unità di ' . $resourceName . ' dal distretto ' . $this->name . ' al distretto ' . $destination->name,
            [
                'url' => url('/districts/' . $this->id),
                'type' => 'info',
            ]
        );
    }

    private function sendSaleNotification(string $resourceName, float $amount, float $totalProfit)
    {
        $government = Citizen::find(2)->user;
        $government->sendNotification(
            'Vendita Risorse Eccedenti',
            'È stata venduta ' . $amount . ' unità di ' . $resourceName . ' per un totale di ' . athel($totalProfit),
            [
                'url' => url('/districts/' . $this->id),
                'type' => 'success',
            ]
        );
    }
}

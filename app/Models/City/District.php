<?php

namespace App\Models\City;

use App\Models\Resource\Resource;
use Illuminate\Database\Eloquent\Model;
use App\Models\Recycling\DistrictRecyclingGoal;

class District extends Model
{
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



    public function transferResourceTo(District $destination, string $resourceName, float $amount): bool
    {
        // Calcola l'efficienza del trasferimento basata sulle infrastrutture
        $efficiency = $this->infrastructures()->avg('efficiency') ?? 1.00;
        $transferAmount = $amount * $efficiency;

        // Recupera la risorsa dal distretto in base alla priorità
        $resource = $this->resources()->where('name', $resourceName)->orderBy('priority', 'asc')->first();

        if (!$resource || $resource->quantity < $transferAmount) {
            return false; // Non ci sono abbastanza risorse per il trasferimento
        }

        // Deduce la quantità di risorse dal distretto corrente
        $resource->quantity -= $transferAmount;
        $resource->save();

        // Aggiunge la quantità al distretto di destinazione
        $destinationResource = $destination->resources()->where('name', $resourceName)->first();
        if (!$destinationResource) {
            // Se il distretto destinatario non ha ancora questa risorsa, la creiamo
            $destination->resources()->create([
                'name' => $resourceName,
                'quantity' => $amount,
            ]);
        } else {
            $destinationResource->quantity += $amount;
            $destinationResource->save();
        }

        // Invia una notifica al governo sul trasferimento
        $this->sendTransferNotification($destination, $resourceName, $amount);

        return true;
    }

    public function calculateProductionEfficiency()
    {
        // Calcola l'efficienza in base all'infrastruttura e al livello tecnologico
        $efficiency = $this->infrastructure_efficiency * $this->technology_level;

        // Limita l'efficienza massima al 200% e minima al 50%
        return max(min($efficiency, 2.0), 0.5);
    }
    public function updateResourceProduction()
    {
        // Recupera tutte le risorse prodotte nel distretto
        $resources = $this->resources;

        foreach ($resources as $resource) {
            // Aumenta l'efficienza di produzione in base alle infrastrutture e tecnologia
            $infrastructureLevel = $this->calculateInfrastructureEfficiency();
            $technologyLevel = $this->calculateTechnologyEfficiency();

            // Calcola il nuovo livello di produzione
            $newProduction = $resource->base_production * ($infrastructureLevel + $technologyLevel);

            // Aggiorna la produzione della risorsa
            $resource->update(['production' => $newProduction]);

            // Invia una notifica in caso di aggiornamenti significativi
            $this->sendEfficiencyNotification($resource, $newProduction);
        }
    }
    public function sellExcessResource(string $resourceName, float $excessAmount): bool
    {
        $resource = $this->resources()->where('name', $resourceName)->first();

        if (!$resource || $resource->quantity < $excessAmount) {
            return false; // Non ci sono abbastanza risorse da vendere
        }

        // Prezzo di vendita per unità di risorsa
        $pricePerUnit = $this->getResourceSalePrice($resourceName);

        // Calcolo del profitto dalla vendita
        $totalProfit = $excessAmount * $pricePerUnit;

        // Aggiornamento della quantità di risorse
        $resource->quantity -= $excessAmount;
        $resource->save();

        // Aggiunge il profitto al bilancio del governo
        $government = Citizen::find(2)->user;
        $government->cash += $totalProfit;
        $government->save();

        // Notifica il governo della vendita di risorse
        $this->sendSaleNotification($resourceName, $excessAmount, $totalProfit);

        return true;
    }
    public function getResourceSalePrice(string $resourceName): float
    {
        // Imposta i prezzi delle risorse
        $prices = [
            'acqua' => 1.5,
            'energia' => 2.0,
            'cibo' => 3.0,
            // Altri prezzi delle risorse
        ];

        return $prices[$resourceName] ?? 1.0; // Prezzo predefinito se non specificato
    }


    private function calculateInfrastructureEfficiency()
    {
        // Supponiamo che ogni distretto abbia un livello di infrastrutture (da 0 a 1)
        return $this->infrastructure_level ?? 0.7; // Valore predefinito
    }


    public function sendEfficiencyNotification($resource, $newProduction)
    {
        // Invia una notifica al governo (utente id 2) sull'aggiornamento
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
        // Invia una notifica all'utente "government" (cittadino id 2)
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

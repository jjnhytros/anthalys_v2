<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = [
        'name',
        'population',
        'area',
        'description',
        'city_id',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function buildings()
    {
        return $this->hasMany(Building::class);
    }
    public function resources()
    {
        return $this->hasMany(Resource::class);
    }
    public function infrastructures()
    {
        return $this->hasMany(Infrastructure::class);
    }

    public function transferResource(District $targetDistrict, $resourceName, $quantity)
    {
        // Troviamo la risorsa nel distretto corrente
        $resource = $this->resources()->where('name', $resourceName)->first();

        // Controlliamo se ci sono risorse sufficienti
        if ($resource && $resource->quantity >= $quantity) {
            // Sottraiamo la quantità trasferita dal distretto corrente
            $resource->quantity -= $quantity;
            $resource->save();

            // Aggiungiamo la quantità al distretto target
            $targetResource = $targetDistrict->resources()->where('name', $resourceName)->first();
            if ($targetResource) {
                $targetResource->quantity += $quantity;
                $targetResource->save();
            } else {
                // Se il distretto target non ha la risorsa, la creiamo
                $targetDistrict->resources()->create([
                    'name' => $resourceName,
                    'quantity' => $quantity,
                    'produced' => 0,
                    'consumed' => 0,
                    'unit' => $resource->unit,
                ]);
            }

            return true; // Trasferimento riuscito
        }

        return false; // Trasferimento fallito per mancanza di risorse
    }
}

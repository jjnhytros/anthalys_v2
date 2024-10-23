<?php

namespace App\Models\City;

use Illuminate\Database\Eloquent\Model;

class Infrastructure extends Model
{
    protected $fillable = [
        'name',
        'type',
        'length',
        'capacity',
        'efficiency',
        'condition',
        'district_id',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
    public function calculateDistributedResource($resourceConsumed)
    {
        // La risorsa distribuita è influenzata dall'efficienza dell'infrastruttura
        $distributedResource = $resourceConsumed * $this->efficiency;

        // Assicurarsi che la risorsa distribuita non superi la capacità dell'infrastruttura
        return min($distributedResource, $this->capacity);
    }

    public function maintain()
    {
        // La manutenzione ripristina la condizione e l'efficienza al massimo
        $this->condition = 1.00;
        $this->efficiency = 1.00;
        $this->save();
    }
}

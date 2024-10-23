<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class ProductionPhase extends Model
{
    protected $fillable = ['name', 'description', 'duration'];

    // Relazione con Alcoholic
    public function alcoholics()
    {
        return $this->belongsToMany(Alcoholic::class, 'alcoholic_production_phases');
    }
}

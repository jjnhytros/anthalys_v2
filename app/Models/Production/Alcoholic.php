<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class Alcoholic extends Model
{
    protected $fillable = [
        'name',
        'batch_size',
        'malt_type',
        'hop_type',
        'yeast_type',
        'water_source',
        'production_phase',
        'fermentation_time',
        'maturation_time',
        'environmental_impact'
    ];

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'alcoholic_ingredients');
    }
    public function productionPhases()
    {
        return $this->belongsToMany(ProductionPhase::class, 'alcoholic_production_phases');
    }
}

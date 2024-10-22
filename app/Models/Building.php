<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $fillable = [
        'name',
        'type',
        'floors',
        'height',
        'energy_consumption',
        'water_consumption',
        'food_consumption',
        'district_id',
    ];
    public function district()
    {
        return $this->belongsTo(District::class);
    }
}

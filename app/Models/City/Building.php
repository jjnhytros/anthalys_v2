<?php

namespace App\Models\City;

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
    public function residents()
    {
        return $this->hasMany(Citizen::class, 'residential_building_id');
    }
    public function workers()
    {
        return $this->hasMany(Citizen::class, 'work_building_id');
    }
    public function citizen()
    {
        return $this->belongsTo(Citizen::class);
    }
}

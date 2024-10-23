<?php

namespace App\Models\Agricolture;

use Illuminate\Database\Eloquent\Model;

class AgriculturalResource extends Model
{
    protected $fillable = ['name', 'quantity', 'daily_production', 'daily_consumption', 'water_consumption', 'energy_consumption', 'district_id'];
}

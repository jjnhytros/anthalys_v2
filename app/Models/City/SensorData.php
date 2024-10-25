<?php

namespace App\Models\City;

use App\Models\Agricolture\Farm;
use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    protected $fillable = ['farm_id', 'drone_id', 'temperature', 'humidity', 'crop_health'];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function drone()
    {
        return $this->belongsTo(Drone::class);
    }
}

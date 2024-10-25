<?php

namespace App\Models\City;

use App\Models\Agricolture\Farm;
use Illuminate\Database\Eloquent\Model;

class Drone extends Model
{
    protected $fillable = ['type', 'battery_level', 'status', 'farm_id'];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    // Metodo per simulare la raccolta di dati
    public function collectData()
    {
        // Simula la raccolta di dati da un drone
        return [
            'temperature' => rand(18, 35), // esempio di temperatura
            'humidity' => rand(40, 70), // esempio di umidità
            'crop_health' => rand(80, 100), // esempio di stato di salute delle colture
        ];
    }
}

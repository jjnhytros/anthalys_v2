<?php

namespace App\Models\City;

use App\Models\Agricolture\Farm;
use Illuminate\Database\Eloquent\Model;

class Robot extends Model
{
    protected $fillable = ['type', 'battery_level', 'status', 'farm_id'];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    // Metodo per automatizzare la semina
    public function plantSeeds()
    {
        // Simula la semina da parte del robot
        return "Robot {$this->id} ha seminato le colture.";
    }

    // Metodo per automatizzare la raccolta
    public function harvestCrops()
    {
        // Simula la raccolta da parte del robot
        return "Robot {$this->id} ha raccolto le colture.";
    }
}

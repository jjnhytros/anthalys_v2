<?php

namespace App\Models\Recycling;

use App\Models\City\Citizen;
use Illuminate\Database\Eloquent\Model;

class RecyclingProgress extends Model
{
    protected $fillable = ['citizen_id', 'waste_type_id', 'quantity'];

    // Relazione con il cittadino
    public function citizen()
    {
        return $this->belongsTo(Citizen::class);
    }

    // Relazione con il tipo di rifiuto
    public function wasteType()
    {
        return $this->belongsTo(WasteType::class);
    }
}

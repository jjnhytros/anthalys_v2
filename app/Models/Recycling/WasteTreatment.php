<?php

namespace App\Models\Recycling;

use Illuminate\Database\Eloquent\Model;

class WasteTreatment extends Model
{
    protected $fillable = [
        'waste_type',
        'treatment_type',
        'output_quantity',
        'output_resource'
    ];

    public function wasteType()
    {
        return $this->belongsTo(WasteType::class, 'waste_type', 'name');
    }
}

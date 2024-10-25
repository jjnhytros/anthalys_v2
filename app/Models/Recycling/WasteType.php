<?php

namespace App\Models\Recycling;

use Illuminate\Database\Eloquent\Model;

class WasteType extends Model
{
    protected $fillable = [
        'name',
        'container_color',
        'description'
    ];

    public function treatments()
    {
        return $this->hasMany(WasteTreatment::class, 'waste_type', 'name');
    }
}

<?php

namespace App\Models\Agricolture;

use Illuminate\Database\Eloquent\Model;

class ProductionReport extends Model
{
    protected $fillable = ['farm_id', 'total_crop_yield', 'total_animal_yield', 'vertical_farming_yield', 'report_period', 'type'];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}

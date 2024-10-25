<?php

namespace App\Models\Agricolture;

use Illuminate\Database\Eloquent\Model;

class ProductionHistory extends Model
{
    public $table = "production_history";
    protected $fillable = ['farm_id', 'total_yield', 'soil_health', 'efficiency'];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}

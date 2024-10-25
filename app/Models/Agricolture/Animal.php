<?php

namespace App\Models\Agricolture;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    protected $fillable = ['name', 'growth_time', 'yield', 'farm_id'];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}

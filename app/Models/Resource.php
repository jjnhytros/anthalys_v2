<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = [
        'name',
        'quantity',
        'produced',
        'consumed',
        'daily_production',
        'unit',
        'district_id',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}

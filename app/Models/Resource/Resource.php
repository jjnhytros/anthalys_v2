<?php

namespace App\Models\Resource;

use App\Models\City\District;
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

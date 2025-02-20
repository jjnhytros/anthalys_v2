<?php

namespace App\Models\Recycling;

use App\Models\City\District;
use Illuminate\Database\Eloquent\Model;

class DistrictRecyclingGoal extends Model
{
    protected $fillable = [
        'district_id',
        'resource_type',
        'target_quantity',
        'current_quantity'
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}

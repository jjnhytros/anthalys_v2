<?php

namespace App\Models\Recycling;

use App\Models\City\Citizen;
use Illuminate\Database\Eloquent\Model;

class RecyclingActivity extends Model
{
    protected $fillable = ['citizen_id', 'resource_type', 'quantity', 'recycled_at', 'bonus'];

    public function citizen()
    {
        return $this->belongsTo(Citizen::class);
    }
}

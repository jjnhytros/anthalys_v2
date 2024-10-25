<?php

namespace App\Models\City;

use Illuminate\Database\Eloquent\Model;

class LoyaltyPoint extends Model
{
    protected $fillable = ['citizen_id', 'points'];

    public function citizen()
    {
        return $this->belongsTo(Citizen::class);
    }
}

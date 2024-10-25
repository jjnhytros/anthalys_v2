<?php

namespace App\Models\Market;

use App\Models\City\Citizen;
use Illuminate\Database\Eloquent\Model;

class Stall extends Model
{
    protected $fillable = ['name', 'description', 'market_id', 'owner_id'];

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function owner()
    {
        return $this->belongsTo(Citizen::class, 'owner_id');
    }
}

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
    public function history()
    {
        return $this->hasMany(ResourceHistory::class);
    }


    public function adjustPrice()
    {
        if ($this->availability < 50) {
            $this->price *= 1.2;  // Aumenta il prezzo del 20% se la disponibilità è bassa
        } elseif ($this->availability > 100) {
            $this->price *= 0.8;  // Riduci il prezzo del 20% se c'è sovrapproduzione
        }
        $this->save();
    }
}

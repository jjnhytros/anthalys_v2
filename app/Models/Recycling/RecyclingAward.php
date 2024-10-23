<?php

namespace App\Models\Recycling;

use App\Models\City\Citizen;
use Illuminate\Database\Eloquent\Model;

class RecyclingAward extends Model
{
    protected $fillable = ['citizen_id', 'award_type', 'year'];

    public function citizen()
    {
        return $this->belongsTo(Citizen::class);
    }
}

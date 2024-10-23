<?php

namespace App\Models\Production;

use App\Models\City\District;
use Illuminate\Database\Eloquent\Model;

class Fertilizer extends Model
{
    protected $fillable = ['district_id', 'type', 'quantity'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}

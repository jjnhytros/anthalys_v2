<?php

namespace App\Models\Recycling;

use App\Models\City\Citizen;
use Illuminate\Database\Eloquent\Model;

class AutoWasteDisposer extends Model
{
    protected $fillable = ['type', 'efficiency', 'citizen_id'];

    public function citizen()
    {
        return $this->belongsTo(Citizen::class);
    }
}

<?php

namespace App\Models\Korfball;

use App\Models\City\Citizen;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name', 'manager_id'];

    public function manager()
    {
        return $this->belongsTo(Citizen::class, 'manager_id');
    }
}

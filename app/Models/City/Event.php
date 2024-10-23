<?php

namespace App\Models\City;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'type',
        'description',
        'impact',
        'active'
    ];
}

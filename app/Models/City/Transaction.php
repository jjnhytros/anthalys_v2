<?php

namespace App\Models\City;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'citizen_id',
        'type',
        'amount',
        'description',
    ];
}

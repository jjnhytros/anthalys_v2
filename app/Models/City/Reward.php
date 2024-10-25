<?php

namespace App\Models\City;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $fillable = ['name', 'points_required'];
}

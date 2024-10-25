<?php

namespace App\Models\City;

use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    protected $fillable = ['title', 'description', 'salary', 'stress_level'];

    public function careers()
    {
        return $this->hasMany(CitizenCareer::class);
    }
}

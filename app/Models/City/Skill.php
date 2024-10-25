<?php

namespace App\Models\City;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = ['name', 'description'];

    public function citizens()
    {
        return $this->belongsToMany(Citizen::class, 'citizen_skills')
            ->withPivot('level', 'experience')
            ->withTimestamps();
    }
}

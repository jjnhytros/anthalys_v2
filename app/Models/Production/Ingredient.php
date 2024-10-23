<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = ['name', 'type', 'quantity', 'unit'];

    // Relazione con Alcoholic
    public function alcoholics()
    {
        return $this->belongsToMany(Alcoholic::class, 'alcoholic_ingredients');
    }
}

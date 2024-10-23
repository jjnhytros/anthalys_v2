<?php

namespace App\Models\City;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    protected $fillable = [
        'name',
        'type',
        'rate',
        'description',
        'active',
    ];

    /**
     * Verifica se la politica è attiva
     */
    public function isActive()
    {
        return $this->active;
    }
}

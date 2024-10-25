<?php

namespace App\Models\Agricolture;

use Illuminate\Database\Eloquent\Model;

class Greenhouse extends Model
{
    protected $fillable = ['type', 'energy_source', 'yield_multiplier'];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
    public function isVerticalFarming()
    {
        return $this->type === 'Verticale';
    }
}

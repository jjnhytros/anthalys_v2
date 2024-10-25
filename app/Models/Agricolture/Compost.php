<?php

namespace App\Models\Agricolture;

use Illuminate\Database\Eloquent\Model;

class Compost extends Model
{
    public $table = "compost";
    protected $fillable = ['farm_id', 'quantity'];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}

<?php

namespace App\Models\Agricolture;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $fillable = ['type', 'value', 'greenhouse_id'];

    public function greenhouse()
    {
        return $this->belongsTo(Greenhouse::class);
    }
}

<?php

namespace App\Models\Recycling;

use Illuminate\Database\Eloquent\Model;

class WasteTreatment extends Model
{
    protected $fillable = ['waste_type', 'treatment_type', 'output_quantity', 'output_resource'];
}

<?php

namespace App\Models\Agricolture;

use App\Models\City\District;
use Illuminate\Database\Eloquent\Model;

class CropRotation extends Model
{
    protected $fillable = ['crop_name', 'season', 'district_id'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceTransfer extends Model
{
    protected $fillable = [
        'source_district_id',
        'target_district_id',
        'resource_id',
        'quantity'
    ];

    public function sourceDistrict()
    {
        return $this->belongsTo(District::class, 'source_district_id');
    }

    public function targetDistrict()
    {
        return $this->belongsTo(District::class, 'target_district_id');
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}

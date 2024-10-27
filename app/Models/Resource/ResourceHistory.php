<?php

namespace App\Models\Resource;

use Illuminate\Database\Eloquent\Model;

class ResourceHistory extends Model
{
    protected $fillable = [
        'resource_id',
        'price',
        'availability',
        'date'
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}

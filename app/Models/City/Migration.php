<?php

namespace App\Models\City;

use Illuminate\Database\Eloquent\Model;

class Migration extends Model
{
    public $table = 'population_migrations';
    protected $fillable = [
        'from_district_id',
        'to_district_id',
        'migrants_count',
    ];

    // Relazione con il distretto di partenza
    public function fromDistrict()
    {
        return $this->belongsTo(District::class, 'from_district_id');
    }

    // Relazione con il distretto di destinazione
    public function toDistrict()
    {
        return $this->belongsTo(District::class, 'to_district_id');
    }
}

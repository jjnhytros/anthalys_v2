<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Citizen extends Model
{
    protected $fillable = [
        'name',
        'is_employed',
        'income',
        'residential_building_id',
        'work_building_id',
        'city_id',
        'cash'
    ];

    // Relazione con l'edificio residenziale (dove il cittadino vive)
    public function residentialBuilding()
    {
        return $this->belongsTo(Building::class, 'residential_building_id');
    }

    // Relazione con l'edificio di lavoro (commerciale o industriale)
    public function workBuilding()
    {
        return $this->belongsTo(Building::class, 'work_building_id');
    }

    // Relazione con la città
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function buildings()
    {
        return $this->hasMany(Building::class, 'citizen_id'); // Assumendo che un cittadino possa avere più edifici
    }
}

<?php

namespace App\Models\City;

use Illuminate\Database\Eloquent\Model;

class InfrastructureMaintenanceHistory extends Model
{
    public $table = 'infrastructure_maintenance_history';
    protected $fillable = [
        'infrastructure_id',
        'maintained_at',
    ];
}

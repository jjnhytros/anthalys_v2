<?php

namespace App\Models\City;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'population',
        'climate',
    ];
    public function districts()
    {
        return $this->hasMany(District::class);
    }

    public function expandDistricts()
    {
        $newDistricts = [
            ['name' => 'Nuovo Quartiere A', 'population' => rand(10000, 50000), 'area' => rand(10, 30)],
            ['name' => 'Nuovo Quartiere B', 'population' => rand(15000, 40000), 'area' => rand(15, 35)],
        ];

        foreach ($newDistricts as $district) {
            $this->districts()->create($district);
        }
    }

    public function expandBuildings($district)
    {
        $newBuildings = [
            ['name' => 'Nuovo Edificio A', 'type' => 'Residenziale', 'floors' => rand(5, 10), 'height' => rand(15, 40), 'energy_consumption' => rand(500, 1000), 'water_consumption' => rand(100, 500), 'food_consumption' => rand(50, 200)],
            ['name' => 'Nuovo Edificio B', 'type' => 'Commerciale', 'floors' => rand(3, 8), 'height' => rand(10, 30), 'energy_consumption' => rand(1000, 3000), 'water_consumption' => rand(300, 1000), 'food_consumption' => 0],
        ];

        foreach ($newBuildings as $building) {
            $district->buildings()->create($building);
        }
    }
}

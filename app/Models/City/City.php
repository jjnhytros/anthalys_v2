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

        $district->buildings()->insert($newBuildings);
    }

    // Impatto complessivo degli Edifici Ricreativi nella città
    public function calculateCityWellbeingImpact()
    {
        return $this->districts->sum->calculateDistrictWellbeingImpact();
    }

    // Impatto complessivo degli Edifici di Comunicazione e Tecnologia nella città
    public function calculateCityTechnologyImpact()
    {
        return $this->districts->sum->calculateDistrictTechnologyImpact();
    }

    // Impatto complessivo delle Infrastrutture di Gestione Rifiuti nella città
    public function calculateCityRecyclingImpact()
    {
        return $this->districts->sum->calculateDistrictRecyclingImpact();
    }

    // Impatto complessivo delle Infrastrutture di Emergenza e Sicurezza nella città
    public function calculateCitySafetyImpact()
    {
        return $this->districts->sum->calculateDistrictSafetyImpact();
    }

    // Impatto complessivo degli Impianti di Ricerca e Innovazione nella città
    public function calculateCityInnovationImpact()
    {
        return $this->districts->sum->calculateDistrictInnovationImpact();
    }

    // Impatti dagli Edifici Civili
    public function calculateCityEducationImpact()
    {
        return $this->districts->sum->calculateDistrictEducationImpact();
    }

    public function calculateCityHealthImpact()
    {
        return $this->districts->sum->calculateDistrictHealthImpact();
    }

    // Impatti dagli Edifici Governativi
    public function calculateCityAdministrativeImpact()
    {
        return $this->districts->sum->calculateDistrictAdministrativeImpact();
    }

    public function calculateCityFiscalContribution()
    {
        return $this->districts->sum->calculateDistrictFiscalContribution();
    }

    // Impatti dagli Edifici Culturali
    public function calculateCityCulturalImpact()
    {
        return $this->districts->sum->calculateDistrictCulturalImpact();
    }

    public function calculateCityEventIncome()
    {
        return $this->districts->sum->calculateDistrictEventIncome();
    }

    // Impatti dalle Infrastrutture Pubbliche
    public function calculateCityTransportImpact()
    {
        return $this->districts->sum->calculateDistrictTransportImpact();
    }
}

<?php

namespace App\Models\City;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    // Attributi riempibili
    protected $fillable = [
        'name',
        'type',
        'floors',
        'height',
        'energy_consumption',
        'water_consumption',
        'food_consumption',
        'capacity',
        'service_quality',
        'administrative_capacity',
        'tax_contribution',
        'cultural_capacity',
        'tourism_attraction',
        'event_income',
        'transport_capacity',
        'energy_output',
        'district_id'
    ];

    // Relazioni
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    // Identificazione del tipo di edificio
    public function isSchool()
    {
        return $this->type === 'scuola';
    }

    public function isHospital()
    {
        return $this->type === 'ospedale';
    }

    public function isGovernmentBuilding()
    {
        return $this->type === 'governativo';
    }

    public function isCulturalBuilding()
    {
        return $this->type === 'culturale';
    }

    public function isTransportInfrastructure()
    {
        return $this->type === 'trasporti';
    }

    public function isEnergyService()
    {
        return $this->type === 'energia';
    }

    public function isRecreational()
    {
        return $this->type === 'ricreativo';
    }

    public function isCommunicationHub()
    {
        return $this->type === 'comunicazione';
    }

    public function isWasteManagement()
    {
        return $this->type === 'gestione_rifiuti';
    }

    public function isEmergencyService()
    {
        return $this->type === 'emergenza';
    }

    public function isSocialHousing()
    {
        return $this->low_income_support;
    }

    public function isElderlyHousing()
    {
        return $this->elderly_support;
    }

    public function isResearchCenter()
    {
        return $this->type === 'ricerca';
    }

    // Calcoli specifici per tipo di edificio
    public function calculateLiteracyImpact()
    {
        return $this->isSchool() ? $this->capacity * $this->service_quality : 0;
    }

    public function calculateInnovationImpact()
    {
        return $this->isResearchCenter() ? $this->research_capacity * $this->innovation_boost : 0;
    }

    public function calculateHealthImpact()
    {
        return $this->isHospital() ? $this->capacity * $this->service_quality : 0;
    }

    public function calculateSafetyImpact()
    {
        return $this->isEmergencyService() ? $this->emergency_capacity * $this->safety_boost : 0;
    }

    public function calculateRecyclingImpact()
    {
        return $this->isWasteManagement() ? $this->waste_capacity * $this->recycling_efficiency : 0;
    }

    public function calculateTechnologyImpact()
    {
        return $this->isCommunicationHub() ? $this->communication_capacity * $this->technology_boost : 0;
    }

    public function calculateAdministrativeImpact()
    {
        return $this->isGovernmentBuilding() ? $this->administrative_capacity : 0;
    }

    public function calculateFiscalContribution()
    {
        return $this->isGovernmentBuilding() ? $this->tax_contribution : 0;
    }

    public function calculateCulturalImpact()
    {
        return $this->isCulturalBuilding() ? $this->cultural_capacity * $this->tourism_attraction : 0;
    }

    public function calculateEventIncome()
    {
        return $this->isCulturalBuilding() ? $this->event_income : 0;
    }

    public function calculateTransportImpact()
    {
        return $this->isTransportInfrastructure() ? $this->transport_capacity : 0;
    }

    public function calculateEnergyProduction()
    {
        return $this->isEnergyService() ? $this->energy_output : 0;
    }

    public function calculateWellbeingImpact()
    {
        return $this->isRecreational() ? $this->recreation_capacity * $this->wellbeing_boost : 0;
    }
}

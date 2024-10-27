<?php

namespace App\Services\City;

class EconomyService
{
    public function calculateIncome($city)
    {
        // Calcola il reddito totale della città
        return $city->population * $city->average_income;
    }

    public function calculateExpenditures($city)
    {
        // Calcola le spese totali della città
        return $city->infrastructure_costs + $city->resource_management_costs;
    }
}

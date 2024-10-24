<?php

namespace App\Jobs;

use App\Models\City\City;
use App\Models\City\Citizen;
use App\Models\City\Building;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SimulatePopulationGrowth implements ShouldQueue
{
    public function handle()
    {
        // Recupera la città
        $city = City::find(1); // Ad esempio Anthalys
        $availableFood = $city->getResource('food');
        $availableWater = $city->getResource('water');
        $availableEnergy = $city->getResource('energy');

        // Capacità totale degli edifici residenziali
        $residentialCapacity = Building::where('city_id', $city->id)
            ->where('type', 'Residenziale')
            ->sum('capacity');

        // Popolazione attuale
        $currentPopulation = $city->citizens->count();

        // Tasso di crescita naturale
        $growthRate = 0.01;

        // Calcolo crescita e limitazione in base alle risorse
        $naturalGrowth = $currentPopulation * $growthRate;
        $resourceLimit = min($availableFood, $availableWater, $availableEnergy);
        $maxPopulationSupported = $resourceLimit * $residentialCapacity;

        if ($currentPopulation + $naturalGrowth > $maxPopulationSupported) {
            $naturalGrowth = $maxPopulationSupported - $currentPopulation;
        }

        // Aggiungi i nuovi cittadini
        $newCitizensCount = floor($naturalGrowth);
        $this->addNewCitizens($city, $newCitizensCount);
    }

    private function addNewCitizens($city, $newCitizensCount)
    {
        for ($i = 0; $i < $newCitizensCount; $i++) {
            Citizen::create([
                'name' => 'Nuovo Cittadino ' . rand(1, 1000),
                'city_id' => $city->id,
                'is_employed' => false,
                'income' => 0,
                'cash' => rand(100, 500),
            ]);
        }
    }
}

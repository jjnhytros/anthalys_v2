<?php

namespace App\Jobs;

use App\Models\City\District;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class UpdateDistrictResourcesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $district;

    public function __construct(District $district)
    {
        $this->district = $district;
    }

    public function handle()
    {
        $consumedEnergy = 0;
        $consumedWater = 0;
        $consumedFood = 0;

        // Calcolo del consumo risorse degli edifici
        foreach ($this->district->buildings as $building) {
            $consumedEnergy += $building->energy_consumption;
            $consumedWater += $building->water_consumption;
        }

        // Consumo del cibo calcolato in base alla popolazione
        $consumedFood = $this->district->population * 1.5;  // Supponiamo 1.5 unità di cibo per abitante

        // Aggiornamento delle risorse nel distretto
        $this->district->energy = max(0, $this->district->energy - $consumedEnergy);
        $this->district->water = max(0, $this->district->water - $consumedWater);
        $this->district->food = max(0, $this->district->food - $consumedFood);
        $this->district->save();
    }
}

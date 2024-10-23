<?php

namespace App\Console\Commands;

use App\Models\City\City;
use Illuminate\Console\Command;

class SimulateCityGrowth extends Command
{
    protected $signature = 'simulate:growth';
    protected $description = 'Simula la crescita della città con nuovi distretti ed edifici';

    public function handle()
    {
        // Otteniamo la città di Anthalys
        $city = City::first();

        // Aggiungiamo nuovi distretti ogni volta che viene eseguita la simulazione
        $city->expandDistricts();

        // Per ogni distretto, aggiungiamo nuovi edifici
        foreach ($city->districts as $district) {
            $city->expandBuildings($district);
        }

        // Calcoliamo la popolazione totale sommando la popolazione dei distretti
        $totalPopulation = $city->districts->sum('population');

        // Aggiorniamo il campo popolazione della città
        $city->population = $totalPopulation;
        $city->save();

        $this->info('La città è cresciuta con nuovi distretti ed edifici.');
    }
}

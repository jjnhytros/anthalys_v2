<?php

namespace App\Console\Commands;

use App\Models\City;
use Illuminate\Console\Command;

class IncreaseResourceProduction extends Command
{
    protected $signature = 'simulate:increase-resource-production';
    protected $description = 'Aumenta la produzione delle risorse per ogni distretto';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Recupera tutte le città
        $cities = City::with('districts.resources')->get();

        foreach ($cities as $city) {
            // Chiama il metodo per aumentare la produzione
            foreach ($city->districts as $district) {
                foreach ($district->resources as $resource) {
                    $incrementFactor = 0.05; // Incremento del 5%
                    $resource->daily_production += $resource->daily_production * $incrementFactor;
                    $resource->save();
                }
            }
        }

        $this->info('Produzione delle risorse incrementata con successo.');
    }
}

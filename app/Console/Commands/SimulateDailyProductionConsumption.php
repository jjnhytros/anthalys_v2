<?php

namespace App\Console\Commands;

use App\Models\City\City;
use App\Models\City\Event;
use App\Models\City\District;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SimulateDailyProductionConsumption extends Command
{
    protected $signature = 'simulate:daily';
    protected $description = 'Simula la produzione e il consumo giornaliero delle risorse, e aggiorna la popolazione totale della città';

    public function handle()
    {
        $this->triggerRandomEvent();  // Aggiungiamo questa chiamata per generare un evento casuale

        // Recupera tutti i distretti con risorse e edifici
        $districts = District::with('resources', 'buildings', 'infrastructures')->get();

        // Recupera l'evento attivo (se esiste)
        $activeEvent = Event::where('active', true)->first();

        // Simula la produzione e il consumo delle risorse
        foreach ($districts as $district) {
            foreach ($district->resources as $resource) {
                // Somma il consumo totale degli edifici in base alla risorsa
                $totalConsumption = $district->buildings->sum("{$resource->name}_consumption");

                // Applica l'impatto dell'evento, se esiste
                if ($activeEvent) {
                    if ($activeEvent->type === 'Tempesta' && $resource->name === 'Energia') {
                        $totalConsumption *= $activeEvent->impact;
                    }
                    if ($activeEvent->type === 'Epidemia' && ($resource->name === 'Cibo' || $resource->name === 'Acqua')) {
                        $totalConsumption *= $activeEvent->impact;
                    }
                }

                // Aggiorna la quantità disponibile della risorsa
                $resource->quantity += $resource->daily_production - $totalConsumption;
                if ($resource->quantity < 0) {
                    $resource->quantity = 0;
                }

                // Salva la risorsa aggiornata
                $resource->save();
            }
        }

        // Simula la degradazione delle infrastrutture
        foreach ($districts as $district) {
            foreach ($district->infrastructures as $infrastructure) {
                // Ridurre la condizione in base all'uso giornaliero
                if ($infrastructure->type === 'Rete Elettrica') {
                    $infrastructure->condition -= 0.01; // Riduzione di 1% al giorno
                } elseif ($infrastructure->type === 'Strada') {
                    $infrastructure->condition -= 0.005; // Riduzione di 0.5% al giorno
                }

                // Se la condizione scende sotto un certo livello, ridurre l'efficienza
                if ($infrastructure->condition < 0.75) {
                    $infrastructure->efficiency = $infrastructure->condition;
                }

                // Salva l'infrastruttura aggiornata
                $infrastructure->save();
            }
        }

        // Simula la crescita della popolazione
        $this->simulatePopulationGrowth($district);
        $this->simulateMigrations($districts);


        // Aggiorna la popolazione totale della città
        $this->updateCityPopulation();

        if ($activeEvent) {
            $this->info('Simulazione completata con l\'evento attivo: ' . $activeEvent->description);
        } else {
            $this->info('Simulazione completata senza eventi attivi.');
        }
    }

    private function triggerRandomEvent()
    {
        // Impostiamo una probabilità di 20% di generare un evento
        if (rand(1, 100) <= 20) {
            // Selezioniamo un evento casuale dal database
            $randomEvent = Event::inRandomOrder()->first();

            // Attiviamo l'evento
            DB::table('events')->update(['active' => false]); // Disattiviamo gli altri eventi
            $randomEvent->active = true;
            $randomEvent->save();

            $this->info('Evento casuale attivato: ' . $randomEvent->description);
        }
    }


    // Funzione per aggiornare la popolazione totale della città
    private function updateCityPopulation()
    {
        $city = City::first();

        // Calcola la popolazione totale sommando la popolazione di tutti i distretti
        $totalPopulation = $city->districts->sum('population');

        // Aggiorna la popolazione totale della città
        $city->population = $totalPopulation;
        $city->save();

        $this->info('Popolazione totale della città aggiornata a ' . number_format($totalPopulation));
    }

    private function simulatePopulationGrowth(District $district)
    {
        // Fattore di crescita della popolazione
        $growthRate = 0.005; // 0.5% per giorno

        // Verifica le risorse disponibili
        $food = $district->resources->where('name', 'Cibo')->first();
        $water = $district->resources->where('name', 'Acqua')->first();
        $energy = $district->resources->where('name', 'Energia')->first();

        if ($food && $water && $energy && $food->quantity > 0 && $water->quantity > 0 && $energy->quantity > 0) {
            // Verifica la condizione delle infrastrutture critiche
            $criticalInfrastructures = $district->infrastructures->whereIn('type', ['Rete Elettrica', 'Rete Idrica']);
            $infrastructureCondition = $criticalInfrastructures->avg('condition');

            if ($infrastructureCondition >= 0.75) {
                // Verifica la capacità degli edifici residenziali
                $totalResidentialCapacity = $district->buildings->where('type', 'Residenziale')->sum('capacity');

                // Se la capacità è sufficiente, la popolazione cresce
                if ($district->population < $totalResidentialCapacity) {
                    $newPopulation = $district->population * (1 + $growthRate);

                    // Aggiorna la popolazione del distretto
                    $district->population = min($newPopulation, $totalResidentialCapacity);
                    $district->save();

                    $this->info('La popolazione del distretto ' . $district->name . ' è cresciuta a ' . number_format($district->population));
                }
            }
        }
    }

    private function simulateMigrations($districts)
    {
        foreach ($districts as $district) {
            $food = $district->resources->where('name', 'Cibo')->first();
            $water = $district->resources->where('name', 'Acqua')->first();
            $energy = $district->resources->where('name', 'Energia')->first();
            $criticalInfrastructures = $district->infrastructures->whereIn('type', ['Rete Elettrica', 'Rete Idrica']);
            $infrastructureCondition = $criticalInfrastructures->avg('condition');

            $citizenCount = $district->citizens->count();
            if ($citizenCount > 0) {
                $unemploymentRate = $district->citizens->where('is_employed', false)->count() / $citizenCount;
            } else {
                $unemploymentRate = 0;
            }

            if ($food->quantity < 500 || $water->quantity < 500 || $energy->quantity < 500 || $infrastructureCondition < 0.5 || $unemploymentRate > 0.25) {
                $migrants = $district->citizens->take(rand(5, 20)); // Numero casuale di cittadini che migrano

                // Trova un distretto con condizioni migliori e maggiori opportunità di lavoro, con distanza inferiore a 100 km
                $betterDistrict = $districts->filter(function ($d) use ($district, $food, $water, $energy, $infrastructureCondition) {
                    $distance = $this->calculateDistance($district, $d);
                    $availableJobs = $d->buildings->whereIn('type', ['Commerciale', 'Industriale'])->sum('capacity') - $d->citizens->count();

                    return $d->resources->where('name', 'Cibo')->first()->quantity > 1000
                        && $d->resources->where('name', 'Acqua')->first()->quantity > 1000
                        && $d->resources->where('name', 'Energia')->first()->quantity > 1000
                        && $d->infrastructures->whereIn('type', ['Rete Elettrica', 'Rete Idrica'])->avg('condition') > 0.75
                        && $availableJobs > 0
                        && $distance < 100; // Migrazione solo entro 100 km
                })->first();

                if ($betterDistrict) {
                    foreach ($migrants as $citizen) {
                        $citizen->residential_building_id = $betterDistrict->buildings->where('type', 'Residenziale')->random()->id;
                        $citizen->save();

                        // Aggiorna le popolazioni dei distretti
                        $district->population--;
                        $betterDistrict->population++;
                        $district->save();
                        $betterDistrict->save();

                        $this->info('Cittadino ' . $citizen->name . ' migrato da ' . $district->name . ' a ' . $betterDistrict->name);
                    }
                }
            }
        }
    }

    private function calculateDistance($districtA, $districtB)
    {
        // Formula di Haversine per calcolare la distanza tra due coordinate geografiche
        $earthRadius = 6371; // Raggio della Terra in km

        $latFrom = deg2rad($districtA->latitude);
        $lonFrom = deg2rad($districtA->longitude);
        $latTo = deg2rad($districtB->latitude);
        $lonTo = deg2rad($districtB->longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
}

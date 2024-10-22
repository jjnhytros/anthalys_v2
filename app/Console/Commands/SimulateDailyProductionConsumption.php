<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Event;
use App\Models\District;
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
}

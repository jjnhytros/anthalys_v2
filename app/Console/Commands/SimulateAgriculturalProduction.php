<?php

namespace App\Console\Commands;

use App\Models\City\District;
use Illuminate\Console\Command;
use App\Models\Agricolture\Season;
use App\Models\Agricolture\AgriculturalTechnique;

class SimulateAgriculturalProduction extends Command
{
    protected $signature = 'simulate:agricultural-production';
    protected $description = 'Simula la produzione e il consumo giornaliero delle risorse agricole nei distretti, tenendo conto delle stagioni, delle risorse necessarie, della rotazione delle colture, dei fertilizzanti e del compost.';

    public function handle()
    {
        // Otteniamo la stagione corrente
        $currentSeason = Season::getCurrentSeason();
        $this->info('Stagione attuale: ' . $currentSeason->name . ' (Fattore di impatto: ' . $currentSeason->impact_factor . ')');
        $techniques = AgriculturalTechnique::all();

        // Recuperiamo tutti i distretti e le risorse agricole
        $districts = District::with('resources', 'buildings', 'cropRotations')->get();

        foreach ($districts as $district) {
            // Gestione della rotazione delle colture
            $cropRotation = $district->cropRotations->first();
            if ($cropRotation) {
                $currentCrop = $cropRotation->getCurrentCrop();
                $this->info('Rotazione delle colture in atto nel distretto ' . $district->name . ': Coltura attuale - ' . $currentCrop->name);
            } else {
                $this->info('Nessuna rotazione delle colture in atto nel distretto ' . $district->name);
                continue; // Se non ci sono rotazioni, passiamo al prossimo distretto
            }

            foreach ($district->resources as $resource) {

                // Se la risorsa è cibo, applichiamo l'impatto della stagione, della rotazione delle colture, e l'effetto dei fertilizzanti e del compost
                if ($resource->name === 'Cibo') {

                    // Applica le tecniche agricole
                    foreach ($resource->agriculturalTechniques as $technique) {
                        $resource->daily_production *= $technique->efficiency_boost;
                        $this->info('Tecnica applicata: ' . $technique->name . ' (Efficienza: ' . $technique->efficiency_boost . ')');
                    }

                    // Applica il fattore della rotazione delle colture
                    $resource->daily_production *= $currentCrop->yield_factor;

                    // Applica il fattore della stagione
                    $resource->daily_production *= $currentSeason->impact_factor;

                    // Applica l'effetto dei fertilizzanti
                    $fertilizer = $district->resources->where('name', 'Fertilizzanti')->first();
                    if ($fertilizer && $fertilizer->quantity > 0) {
                        $fertilizerImpact = 1.10; // I fertilizzanti migliorano la produzione del 10%
                        $resource->daily_production *= $fertilizerImpact;
                        $fertilizer->quantity -= 10; // Riduce la quantità di fertilizzanti
                        $fertilizer->save();
                        $this->info('Fertilizzante applicato nel distretto ' . $district->name . ' (Produzione aumentata del 10%)');
                    }

                    // Applica l'effetto del compost
                    $compost = $district->resources->where('name', 'Compost')->first();
                    if ($compost && $compost->quantity > 0) {
                        $compostImpact = 1.05; // Il compost aumenta la produzione del 5%
                        $resource->daily_production *= $compostImpact;
                        $compost->quantity -= 5; // Riduce la quantità di compost
                        $compost->save();
                        $this->info('Compost applicato nel distretto ' . $district->name . ' (Produzione aumentata del 5%)');
                    }
                }

                // Simula il compostaggio e i fertilizzanti naturali
                if ($resource->name === 'Compost') {
                    $resource->quantity += $resource->daily_production;  // Il compost viene generato giornalmente
                }

                // Calcola il consumo totale di acqua ed energia per la produzione agricola
                $water = $district->resources->where('name', 'Acqua')->first();
                $energy = $district->resources->where('name', 'Energia')->first();

                if ($water && $energy) {
                    $waterConsumption = $district->buildings->sum('water_consumption');
                    $energyConsumption = $district->buildings->sum('energy_consumption');

                    // Aggiorniamo la quantità disponibile di acqua ed energia
                    $water->quantity -= $waterConsumption;
                    $energy->quantity -= $energyConsumption;

                    // Evitiamo che la quantità scenda sotto zero
                    if ($water->quantity < 0) {
                        $water->quantity = 0;
                    }
                    if ($energy->quantity < 0) {
                        $energy->quantity = 0;
                    }

                    // Salviamo le risorse aggiornate
                    $water->save();
                    $energy->save();
                }

                // Aggiorniamo la quantità disponibile di cibo
                $resource->quantity += $resource->daily_production - $resource->daily_consumption;

                // Evitiamo che la quantità scenda sotto zero
                if ($resource->quantity < 0) {
                    $resource->quantity = 0;
                }

                // Salviamo le modifiche
                $resource->save();

                // Mostriamo informazioni sul distretto e sulle risorse
                $this->info('Produzione e consumo aggiornati per ' . $resource->name . ' nel distretto ' . $district->name);
            }
        }

        $this->info('Simulazione della produzione agricola completata!');
    }
}

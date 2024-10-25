<?php

namespace App\Jobs;

use App\Models\City\Message;
use Illuminate\Bus\Queueable;
use App\Models\City\SensorData;
use App\Models\Agricolture\Farm;
use App\Models\Agricolture\Sensor;
use App\Models\Market\MarketProduct;
use App\Models\Agricolture\Greenhouse;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Agricolture\ProductionHistory;

class UpdateFarmProduction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $farm;

    public function __construct(Farm $farm)
    {
        $this->farm = Farm::with(['crops', 'animals', 'greenhouses'])->find($farm->id);
    }
    public function handle()
    {
        $this->handleProduction($this->farm);
        $this->distributeToMarkets($this->farm);
        $this->monitorFarm($this->farm);
    }

    public function handleProduction()
    {
        // Far volare i droni e raccogliere dati
        foreach ($this->farm->drones as $drone) {
            $data = $drone->collectData();

            // Registra i dati raccolti dal drone
            SensorData::create([
                'farm_id' => $this->farm->id,
                'drone_id' => $drone->id,
                'temperature' => $data['temperature'],
                'humidity' => $data['humidity'],
                'crop_health' => $data['crop_health'],
            ]);

            // Notifica sui dati raccolti
            Message::create([
                'sender_id' => 2,
                'recipient_id' => $this->farm->owner_id,
                'subject' => 'Dati Raccolti dal Drone',
                'body' => "Il drone {$drone->id} ha raccolto dati sulla fattoria '{$this->farm->name}'.",
                'is_read' => false,
                'is_archived' => false,
                'is_notification' => true,
                'created_at' => now(),
            ]);
        }

        // Esegui il monitoraggio per ogni serra associata alla fattoria
        foreach ($this->farm->greenhouses as $greenhouse) {
            $moisture_sensor = Sensor::where('greenhouse_id', $greenhouse->id)->where('type', 'Umidità')->first();
            $nutrient_sensor = Sensor::where('greenhouse_id', $greenhouse->id)->where('type', 'Nutrienti')->first();

            // Automazione dell'irrigazione
            if ($moisture_sensor && $moisture_sensor->value < 30.0) { // Soglia di umidità bassa
                $this->irrigate($greenhouse);
            }

            // Automazione della fertilizzazione
            if ($nutrient_sensor && $nutrient_sensor->value < 15.0) { // Soglia di nutrienti bassa
                $this->fertilize($greenhouse);
            }
        }

        // Calcolo della produzione agricola in base alla salute del suolo e all'efficienza
        foreach ($this->farm->crops as $crop) {
            $soil_health_factor = $this->farm->soil_health;
            $efficiency_factor = $this->farm->efficiency;
            $technology_bonus = 1.10; // Bonus dovuto a tecnologie avanzate, se presenti

            // Riduzione della resa se la salute del suolo è bassa
            if ($soil_health_factor < 0.5) {
                $soil_health_factor *= 0.75; // Penalità del 25% se sotto il livello critico
            }

            // Verifica se la coltura è associata a una serra e applica il moltiplicatore di resa
            $greenhouse = Greenhouse::where('farm_id', $this->farm->id)->first();
            $yield_multiplier = $greenhouse ? $greenhouse->yield_multiplier : 1.0;
            $space_efficiency = $greenhouse ? $greenhouse->space_efficiency : 1.0;

            // Moltiplicatore aggiuntivo per la coltivazione verticale
            if ($greenhouse && $greenhouse->isVerticalFarming()) {
                $yield_multiplier *= 1.5; // Esempio: +50% di rendimento per coltivazione verticale
            }

            // Calcolo della resa
            $crop->yield = $crop->yield * $soil_health_factor * $efficiency_factor * $technology_bonus * $yield_multiplier * $space_efficiency;
            $crop->save();
        }

        // Simile logica per gli animali nella fattoria
        foreach ($this->farm->animals as $animal) {
            $animal_yield = $animal->yield * $efficiency_factor * $technology_bonus;
            $animal->update(['yield' => $animal_yield]);
        }

        // Simulazione del tempo per la crescita delle colture
        foreach ($this->farm->crops as $crop) {
            if ($crop->growth_time <= now()->diffInDays($crop->planted_at)) {
                $crop->status = 'ready_for_harvest';
                $crop->save();

                Message::create([
                    'sender_id' => 2,
                    'recipient_id' => $this->farm->owner_id,
                    'subject' => 'Coltura Pronta per il Raccolto',
                    'body' => "La coltura '{$crop->name}' è pronta per essere raccolta nella fattoria '{$this->farm->name}'.",
                    'is_read' => false,
                    'is_archived' => false,
                    'is_notification' => true,
                    'created_at' => now(),
                ]);
            }
        }

        // Possibile riduzione della salute del suolo dopo ogni ciclo
        $this->farm->update(['soil_health' => max(0.5, $this->farm->soil_health - 0.05)]);

        // Salva la produzione attuale nel registro storico
        ProductionHistory::create([
            'farm_id' => $this->farm->id,
            'total_yield' => $this->farm->crops->sum('yield') + $this->farm->animals->sum('yield'),
            'soil_health' => $this->farm->soil_health,
            'efficiency' => $this->farm->efficiency,
            'created_at' => now(),
        ]);

        // Notifica di completamento del ciclo
        Message::create([
            'sender_id' => 2, // ID del governo
            'recipient_id' => $this->farm->owner_id,
            'subject' => 'Completamento Ciclo di Produzione',
            'body' => "Il ciclo di produzione nella fattoria '{$this->farm->name}' è stato completato.",
            'is_read' => false,
            'is_archived' => false,
            'is_notification' => true,
            'created_at' => now(),
        ]);

        // Verifica se la salute del suolo è critica e invia una notifica
        if ($this->farm->soil_health < 0.5) {
            Message::create([
                'sender_id' => 2,
                'recipient_id' => $this->farm->owner_id,
                'subject' => 'Salute del Suolo Critica',
                'body' => "La salute del suolo nella fattoria '{$this->farm->name}' è scesa sotto il livello critico. Si consiglia di intervenire immediatamente.",
                'is_read' => false,
                'is_archived' => false,
                'is_notification' => true,
                'created_at' => now(),
            ]);
        }

        // Notifica per bassa efficienza delle infrastrutture
        if ($this->farm->efficiency < 0.7) {
            Message::create([
                'sender_id' => 2,
                'recipient_id' => $this->farm->owner_id,
                'subject' => 'Bassa Efficienza delle Infrastrutture',
                'body' => "L'efficienza della fattoria '{$this->farm->name}' è scesa sotto il livello ottimale. Si consiglia di effettuare manutenzioni.",
                'is_read' => false,
                'is_archived' => false,
                'is_notification' => true,
                'created_at' => now(),
            ]);
        }

        // Notifica per livello acqua basso
        if ($this->farm->current_water_level < 20) {
            Message::create([
                'sender_id' => 2,
                'recipient_id' => $this->farm->owner_id,
                'subject' => 'Livello Acqua Basso',
                'body' => "Il livello di acqua nella fattoria '{$this->farm->name}' è sceso sotto il livello critico. Si consiglia di ripristinare le risorse idriche.",
                'is_read' => false,
                'is_archived' => false,
                'is_notification' => true,
                'created_at' => now(),
            ]);
        }
    }

    public function distributeToMarkets(Farm $farm)
    {
        $products = $farm->crops->map(function ($crop) {
            return [
                'name' => $crop->name,
                'quantity' => $crop->yield,
                'price' => rand(5, 20), // Prezzo casuale
                'market_id' => 1, // Assumendo un mercato disponibile
            ];
        });

        foreach ($products as $product) {
            MarketProduct::create($product);
        }
    }

    public function monitorFarm(Farm $farm)
    {
        foreach ($farm->drones as $drone) {
            $data = $drone->collectData();

            // Registra i dati di monitoraggio
            SensorData::create([
                'farm_id' => $farm->id,
                'drone_id' => $drone->id,
                'temperature' => $data['temperature'],
                'humidity' => $data['humidity'],
                'crop_health' => $data['crop_health'],
            ]);
        }

        // Verifica lo stato delle risorse
        $this->checkResources($farm);
    }

    public function checkResources(Farm $farm)
    {
        if ($farm->current_water_level < 20) {
            Message::create([
                'sender_id' => 2,
                'recipient_id' => $farm->owner_id,
                'subject' => 'Livello Acqua Critico',
                'body' => "Il livello dell'acqua nella fattoria '{$farm->name}' è basso.",
                'is_read' => false,
                'is_archived' => false,
                'is_notification' => true,
                'created_at' => now(),
            ]);
        }
    }



    public function irrigate($greenhouse)
    {
        // Logica per avviare l'irrigazione
        $greenhouse->update(['current_water_level' => $greenhouse->current_water_level + 50]); // Esempio di aumento
        Message::create([
            'sender_id' => 2, // ID del governo
            'recipient_id' => $this->farm->owner_id,
            'subject' => 'Irrigazione Automatica Attivata',
            'body' => "L'irrigazione è stata attivata automaticamente nella serra '{$greenhouse->type}'.",
            'is_read' => false,
            'is_archived' => false,
            'is_notification' => true,
            'created_at' => now(),
        ]);
    }

    public function fertilize($greenhouse)
    {
        // Logica per avviare la fertilizzazione
        $greenhouse->update(['soil_health' => min(1.0, $greenhouse->soil_health + 0.10)]); // Incremento della salute del suolo
        Message::create([
            'sender_id' => 2, // ID del governo
            'recipient_id' => $this->farm->owner_id,
            'subject' => 'Fertilizzazione Automatica Attivata',
            'body' => "La fertilizzazione è stata attivata automaticamente nella serra '{$greenhouse->type}'.",
            'is_read' => false,
            'is_archived' => false,
            'is_notification' => true,
            'created_at' => now(),
        ]);
    }
}

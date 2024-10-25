<?php

namespace App\Jobs;

use App\Models\City\Message;
use App\Models\City\District;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class MonitorResourceLevelsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $district;

    public function __construct(District $district)
    {
        $this->district = $district;
    }

    public function handle()
    {
        // Recupera tutte le risorse del distretto
        $resources = $this->district->resources;

        foreach ($resources as $resource) {
            if ($resource->quantity < $resource->min_threshold) {
                // Crisi di carenza di risorse
                $this->triggerCrisis('carenza', $resource);
            }

            if ($resource->quantity > $resource->max_capacity) {
                // Crisi di sovrapproduzione
                $this->triggerCrisis('sovrapproduzione', $resource);
            }

            if ($resource->quantity < $resource->critical_level) {
                // Cerca altri distretti con risorse in surplus
                $donorDistricts = District::whereHas('resources', function ($query) use ($resource) {
                    $query->where('name', $resource->name)
                        ->where('quantity', '>', $resource->critical_level);
                })->get();

                // Effettua il trasferimento della risorsa
                foreach ($donorDistricts as $donor) {
                    $transferAmount = min($donor->resources->where('name', $resource->name)->first()->quantity, $resource->critical_level);
                    $donor->transferResourceTo($this->district, $resource->name, $transferAmount);
                }
            }
        }
    }

    public function triggerCrisis($type, $resource)
    {
        $description = ($type == 'carenza') ?
            "Carenza di {$resource->name} nel distretto {$this->district->name}" :
            "Sovrapproduzione di {$resource->name} nel distretto {$this->district->name}";

        // Invia una notifica per la crisi
        Message::create([
            'sender_id' => 2, // ID del governo
            'recipient_id' => $this->district->manager_id,
            'subject' => ucfirst($type) . ' di Risorse',
            'body' => $description,
            'is_read' => false,
            'is_archived' => false,
            'is_notification' => true,
            'created_at' => now(),
        ]);

        // Logica aggiuntiva per bilanciare la domanda e pianificare la risposta alla crisi
        if ($type == 'carenza') {
            // Bilanciamento della domanda, avvio di piani di emergenza
            $this->handleResourceShortage($resource);
        } elseif ($type == 'sovrapproduzione') {
            // Gestione della sovrapproduzione
            $this->handleOverproduction($resource);
        }
    }

    public function handleResourceShortage($resource)
    {
        // Trova distretti con surplus di risorse
        $surplus_districts = District::whereHas('resources', function ($query) use ($resource) {
            $query->where('name', $resource->name)->where('quantity', '>', $resource->max_capacity);
        })->get();

        foreach ($surplus_districts as $surplus_district) {
            // Quantità trasferibile (non trasferire più di quanto disponibile)
            $transferAmount = min($surplus_district->{$resource->name}, $resource->max_capacity - $resource->quantity);

            // Avvia il trasferimento delle risorse
            TransferResourcesJob::dispatch($surplus_district, $this->district, $resource->name, $transferAmount);
        }
    }

    public function handleOverproduction($resource)
    {
        // Trova distretti con carenza di risorse
        $deficit_districts = District::whereHas('resources', function ($query) use ($resource) {
            $query->where('name', $resource->name)->where('quantity', '<', $resource->min_threshold);
        })->get();

        foreach ($deficit_districts as $deficit_district) {
            // Quantità trasferibile
            $transferAmount = min($resource->quantity, $deficit_district->{$resource->name} - $resource->min_threshold);

            // Avvia il trasferimento delle risorse
            TransferResourcesJob::dispatch($this->district, $deficit_district, $resource->name, $transferAmount);
        }
    }
}

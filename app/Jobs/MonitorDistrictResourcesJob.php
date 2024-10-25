<?php

namespace App\Jobs;

use App\Models\City\Citizen;
use App\Models\User;
use App\Models\City\Message;
use App\Models\City\District;
use Illuminate\Bus\Queueable;
use App\Notifications\ResourceAlert;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;

class MonitorDistrictResourcesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $district;

    /**
     * Crea un nuovo job.
     */
    public function __construct(District $district)
    {
        $this->district = $district;
    }

    /**
     * Esegui il job.
     */
    public function handle()
    {
        // Controlla se le risorse del distretto sono critiche
        $resources = $this->district->resources;

        foreach ($resources as $resource) {
            if ($resource->quantity <= $resource->critical_level) {
                // Invia una notifica personalizzata al governo (utente id 2) per risorse critiche
                $government = Citizen::find(2)->user; // Recupera il governo (utente id 2)

                foreach ($resources as $resource) {
                    if ($resource->quantity <= $resource->critical_level) {
                        $government->sendNotification(
                            'Risorsa Critica',
                            'La risorsa ' . $resource->name . ' nel distretto ' . $this->district->name . ' ha raggiunto un livello critico.',
                            [
                                'url' => url('/districts/' . $this->district->id),
                                'type' => 'warning',
                            ]
                        );
                    }
                }
                $this->district->updateResourceProduction();
            }
        }
    }

    private function sendEfficiencyNotification($district, $resourceName, $efficiency)
    {
        // Usa il metodo di notifica personalizzato implementato
        $government = Citizen::find(2); // ID del cittadino "government"
        $subject = "Aggiornamento Efficienza Trasferimento Risorse";
        $message = "Il distretto {$district->name} ha migliorato l'efficienza del trasferimento della risorsa {$resourceName}. Efficienza attuale: {$efficiency}.";
        $government->sendNotification($subject, $message);
    }
}

<?php

namespace App\Jobs;

use App\Models\City\Event;
use App\Models\Agricolture\Farm;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SimulateUnexpectedEvent implements ShouldQueue
{
    public function handle()
    {
        // Tipi di eventi possibili
        $event_types = ['Tempesta', 'Alluvione', 'Incendio', 'Epidemia'];
        $type = $event_types[array_rand($event_types)];

        // Gravità casuale
        $severity = rand(1, 100) / 100;

        // Impatto casuale
        $impact = rand(5, 50) / 100;

        // Seleziona una fattoria casuale
        $farm = Farm::inRandomOrder()->first();

        // Crea l'evento
        $event = Event::create([
            'type' => $type,
            'description' => "Evento inatteso: $type",
            'impact' => $impact,
            'severity' => $severity,
            'affected_farm_id' => $farm->id,
            'active' => true,
        ]);

        // Applica l'impatto alla fattoria
        $farm->applyEventImpact($event);
    }
}

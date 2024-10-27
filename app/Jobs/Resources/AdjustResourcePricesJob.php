<?php

namespace App\Jobs\Resources;

use App\Models\Resource\Resource;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdjustResourcePricesJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    public function handle()
    {
        $resources = Resource::all();

        foreach ($resources as $resource) {
            $resource->adjustPrice();  // Aggiorna il prezzo in base alla disponibilità
        }
    }
}

<?php

namespace App\Jobs;

use App\Models\City\District;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MonitorResourceSurplusJob implements ShouldQueue
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
            // Verifica se la quantità supera il limite di surplus
            if ($resource->quantity > $resource->surplus_limit) {
                // Vende l'eccesso di risorse
                $excessAmount = $resource->quantity - $resource->surplus_limit;
                $this->district->sellExcessResource($resource->name, $excessAmount);
            }
        }
    }
}

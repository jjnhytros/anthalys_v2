<?php

namespace App\Jobs;

use App\Models\City\District;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MonitorResourceDeficitJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $district;

    public function __construct(District $district)
    {
        $this->district = $district;
    }

    public function handle()
    {
        $resources = $this->district->resources;

        foreach ($resources as $resource) {
            // Verifica se la risorsa è in deficit
            if ($resource->quantity < $resource->deficit_limit) {
                // Trova un altro distretto con un surplus della stessa risorsa
                $surplusDistrict = District::whereHas('resources', function ($query) use ($resource) {
                    $query->where('name', $resource->name)
                        ->where('quantity', '>', $resource->surplus_limit);
                })->first();

                if ($surplusDistrict) {
                    // Calcola la quantità da trasferire
                    $transferAmount = min(
                        $surplusDistrict->resources()->where('name', $resource->name)->first()->quantity,
                        $resource->deficit_limit - $resource->quantity
                    );

                    // Trasferisce le risorse
                    $surplusDistrict->transferResourceTo($resource->name, $this->district, $transferAmount);
                }
            }
        }
    }
}

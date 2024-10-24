<?php

namespace App\Jobs;

use App\Models\City\District;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


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
}

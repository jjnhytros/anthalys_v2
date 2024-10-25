<?php

namespace App\Jobs\MegaWarehouse;

use Illuminate\Bus\Queueable;
use App\Models\Agricolture\Farm;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Agricolture\CompostStorage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DistributeCompostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $compostStorage = CompostStorage::first();
        if ($compostStorage && $compostStorage->available_compost > 0) {
            $farms = Farm::all();
            $compostPerFarm = $compostStorage->available_compost / max($farms->count(), 1);

            foreach ($farms as $farm) {
                $farm->soil_health = min(1.0, $farm->soil_health + $compostPerFarm);
                $farm->save();
            }

            // Azzeramento del compost disponibile dopo la distribuzione
            $compostStorage->available_compost = 0;
            $compostStorage->save();
        }
    }
}

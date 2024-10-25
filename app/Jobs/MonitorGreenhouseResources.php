<?php

namespace App\Jobs;

use App\Models\Agricolture\Greenhouse;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MonitorGreenhouseResources implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $greenhouse;

    public function __construct(Greenhouse $greenhouse)
    {
        $this->greenhouse = $greenhouse;
    }

    public function handle()
    {
        // Aggiorna i livelli di risorse (energia, acqua, nutrienti)
        if ($this->greenhouse->needsWater()) {
            $this->greenhouse->provideWater();
        }
        if ($this->greenhouse->needsNutrients()) {
            $this->greenhouse->addNutrients();
        }
        $this->greenhouse->save();
    }
}

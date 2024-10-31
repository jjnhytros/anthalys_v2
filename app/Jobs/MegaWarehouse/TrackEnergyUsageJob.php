<?php

namespace App\Jobs\MegaWarehouse;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Models\MegaWarehouse\Warehouse;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TrackEnergyUsageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $warehouse;
    protected $energyPerOperation;

    public function __construct(Warehouse $warehouse, $energyPerOperation = 5) // 5 kWh per esempio
    {
        $this->warehouse = $warehouse;
        $this->energyPerOperation = $energyPerOperation;
    }

    public function handle()
    {
        // Registra il consumo di energia per un'operazione
        $this->warehouse->recordEnergyUsage($this->energyPerOperation);
    }
}

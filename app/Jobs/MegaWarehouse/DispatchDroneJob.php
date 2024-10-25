<?php

namespace App\Jobs\MegaWarehouse;

use Illuminate\Bus\Queueable;
use App\Models\Market\OnlineOrder;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DispatchDroneJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    public function __construct(OnlineOrder $order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        sleep(5); // Simulazione del tempo di consegna

        // Aggiorna lo stato dell'ordine come "consegnato"
        $this->order->update(['status' => 'delivered']);
    }
}

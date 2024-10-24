<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Resource\ResourceTransferController;

class SimulateResourceRedistribution extends Command
{
    protected $signature = 'simulate:resource-redistribution';
    protected $description = 'Redistribuisce automaticamente le risorse tra i distretti';

    public function handle()
    {
        $controller = new ResourceTransferController();
        $controller->redistributeResources();

        $this->info('Redistribuzione delle risorse completata!');
    }
}

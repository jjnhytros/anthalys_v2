<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CityController;

class AutoTransferResources extends Command
{
    protected $signature = 'simulate:auto-transfer-resources';
    protected $description = 'Trasferisce automaticamente le risorse tra i distretti in base al surplus e deficit';

    public function handle()
    {
        // Chiama il metodo dal controller per eseguire il trasferimento automatico
        app(CityController::class)->autoTransferResources();

        $this->info('Trasferimento automatico di risorse completato.');
    }
}

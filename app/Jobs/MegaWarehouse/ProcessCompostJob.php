<?php

namespace App\Jobs\MegaWarehouse;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Agricolture\CompostStorage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessCompostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $compostStorage = CompostStorage::first();
        if ($compostStorage) {
            $compostStorage->processCompost();
        }
    }
}

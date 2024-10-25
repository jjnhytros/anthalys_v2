<?php

namespace App\Jobs;

use App\Models\Agricolture\Crop;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MonitorCropGrowth implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $crop;

    public function __construct(Crop $crop)
    {
        $this->crop = $crop;
    }

    public function handle()
    {
        // Controlla lo stato di crescita della coltura e aggiorna
        if ($this->crop->isReadyForNextStage()) {
            $this->crop->advanceGrowthStage();
            $this->crop->save();
        }
    }
}

<?php

namespace App\Jobs\Resources;

use App\Models\City\District;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Resources\ResourcePredictionService;

class BalanceResourcesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $predictionService;

    public function __construct()
    {
        $this->predictionService = new ResourcePredictionService();
    }

    public function handle()
    {
        $districts = District::all();

        // Predice la domanda per ciascun distretto
        foreach ($districts as $district) {
            $district->current_demand = $this->predictionService->predictDemand($district);
            $district->save();
        }

        // Bilanciamento delle risorse tra i distretti
        foreach ($districts as $district) {
            if ($district->surplus < $district->current_demand) {
                $deficit = $district->current_demand - $district->surplus;

                foreach ($districts->where('priority', '>=', $district->priority)->where('surplus', '>', 'current_demand') as $donorDistrict) {
                    $transferAmount = min($deficit, $donorDistrict->surplus - $donorDistrict->current_demand);
                    $donorDistrict->surplus -= $transferAmount;
                    $district->surplus += $transferAmount;
                    $deficit -= $transferAmount;

                    $donorDistrict->save();
                    $district->save();

                    if ($deficit <= 0)
                        break;
                }
            }
        }
    }
}

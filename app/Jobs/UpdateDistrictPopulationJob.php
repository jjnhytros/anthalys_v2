<?php

namespace App\Jobs;

use App\Models\City\District;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateDistrictPopulationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $districtId;

    public function __construct(int $districtId)
    {
        $this->districtId = $districtId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Recupera il distretto dal database usando l'ID
        $district = District::find($this->districtId);

        if ($district) {
            // Logica per aggiornare la popolazione del distretto
            $populationGrowth = $this->calculatePopulationGrowth($district);
            $district->population += $populationGrowth;
            $district->save();
        }
    }

    protected function calculatePopulationGrowth(District $district)
    {
        // Implementa la logica di crescita della popolazione
        $growthRate = 0.02; // Un tasso di crescita fittizio del 2% (puoi adattarlo)
        return round($district->population * $growthRate);
    }
}

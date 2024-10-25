<?php

use App\Models\City\District;
use App\Models\Agricolture\Crop;
use App\Models\Agricolture\Farm;
use Illuminate\Support\Facades\Schedule;

Schedule::command('simulate:daily')->daily();
Schedule::command('simulate:growth')->weekly();
Schedule::command('simulate:taxescollect')->daily();
Schedule::command('simulate:increase-resource-production')->daily();
Schedule::command('simulate:auto-transfer-resources')->hourly();
Schedule::command('simulate:agricultural-production')->hourly();
Schedule::command('simulate:government-policies')->daily();
Schedule::command('simulate:government-policies weekly')->weeklyOn(1, '8:00');
Schedule::command('simulate:government-policies monthly')->monthlyOn(1, '8:00');
Schedule::command('anthalys:generate-government-report')->yearlyOn(12, 31, '23:59');

Schedule::job(new \App\Jobs\SimulatePopulationGrowth())->monthly();
Schedule::call(function () {
    $districts = District::all();
    foreach ($districts as $district) {
        \App\Jobs\UpdateDistrictPopulationJob::dispatch($district->id);
    }
})->daily();

Schedule::call(function () {
    \App\Jobs\MonitorCropGrowth::dispatch();
})->everyThirtyMinutes();

Schedule::call(function () {
    $districts = District::all();
    foreach ($districts as $district) {
        \App\Jobs\UpdateDistrictResourcesJob::dispatch($district);
        \App\Jobs\MonitorDistrictResourcesJob::dispatch($district);
        \App\Jobs\MonitorResourceLevelsJob::dispatch($district);
        \App\Jobs\MonitorResourceSurplusJob::dispatch($district);
        \App\Jobs\MonitorResourceDeficitJob::dispatch($district);
        \App\Jobs\MonitorGreenhouseResources::dispatch();
    }
})->hourly();
Schedule::job(new \App\Jobs\SimulateUnexpectedEvent())->hourly();

Schedule::call(function () {
    $farms = Farm::all();
    foreach ($farms as $farm) {
        \App\Jobs\UpdateFarmProduction::dispatch($farm);
    }
})->daily();
Schedule::job(new \App\Jobs\UpdateProductPricesJob())->daily();
Schedule::job(new \App\Jobs\MegaWarehouse\CheckAndRestockProductsJob())->dailyAt('01:00');
Schedule::job(new \App\Jobs\MegaWarehouse\ProcessWarehouseWasteJob())->daily();

Schedule::job(new \App\Jobs\MegaWarehouse\ProcessCompostJob())->weekly();

Schedule::call(function () {
    \App\Jobs\GenerateMonthlyProductionReport::dispatch();
    \App\Jobs\MegaWarehouse\GenerateWarehouseReportJob::dispatch();
    \App\Jobs\MegaWarehouse\DistributeCompostJob::dispatch();
})->monthlyOn(1, '00:00');

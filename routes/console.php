<?php

use App\Models\City\District;
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


// Every x minutes
Schedule::call(function () {
    \App\Jobs\MonitorCropGrowth::dispatch();
})->everyThirtyMinutes();

// Hourly
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
    app(\App\Jobs\Resources\BalanceResourcesJob::class);
    app(\App\Jobs\SimulateUnexpectedEvent::class);
    app(\App\Http\Controllers\Resource\ResourceController::class)->checkEmergencyPlans();
})->hourly();

// Daily
Schedule::call(function () {
    $farms = Farm::all();
    foreach ($farms as $farm) {
        \App\Jobs\UpdateFarmProduction::dispatch($farm);
    }
    $districts = District::all();
    foreach ($districts as $district) {
        \App\Jobs\UpdateDistrictPopulationJob::dispatch($district->id);
    }
    app(\App\Http\Controllers\Resource\ResourceController::class)->sendResourceAlerts();
    app(\App\Http\Controllers\Resource\ResourceController::class)->updateResourceHistory();
    app(\App\Jobs\UpdateProductPricesJob::class);
    app(\App\Jobs\MegaWarehouse\CheckAndRestockProductsJob::class);
    app(\App\Jobs\MegaWarehouse\ProcessWarehouseWasteJob::class);
    app(\App\Jobs\MegaWarehouse\MonitorExpiringProductsJob::class);
    app(\App\Jobs\MegaWarehouse\MonitorPackagedFoodStockJob::class);
    app(\App\Jobs\Resources\AdjustResourcePricesJob::class);
    app(\App\Jobs\Resources\AutomatedResourceTransferJob::class);
})->daily();

// Weekly
Schedule::call(function () {
    \App\Jobs\MegaWarehouse\ProcessCompostJob::class;
})->weekly();

// Montly
Schedule::call(function () {
    app(\App\Jobs\SimulatePopulationGrowth::class);
})->monthly();

// Montly at
Schedule::call(function () {
    \App\Jobs\GenerateMonthlyProductionReport::dispatch();
    \App\Jobs\MegaWarehouse\GenerateWarehouseReportJob::dispatch();
    \App\Jobs\MegaWarehouse\DistributeCompostJob::dispatch();
})->monthlyOn(1, '00:00');

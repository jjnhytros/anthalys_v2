<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\City\PolicyController;
use App\Http\Controllers\City\CityController;
use App\Http\Controllers\City\CitizenController;
use App\Http\Controllers\City\DistrictController;
use App\Http\Controllers\City\MigrationController;
use App\Http\Controllers\Recycling\BonusController;
use App\Http\Controllers\Resource\ResourceController;
use App\Http\Controllers\City\InfrastructureController;
use App\Http\Controllers\Recycling\RecyclingController;
use App\Http\Controllers\Recycling\WasteTypeController;
use App\Http\Controllers\Production\AlcoholicController;
use App\Http\Controllers\Agricolture\AgriculturalController;
use App\Http\Controllers\Recycling\WasteTreatmentController;
use App\Http\Controllers\Resource\ResourceTransferController;
use App\Http\Controllers\Recycling\AutoWasteDisposerController;


// Bilancio del Governo
Route::get('/api/government/balance', [CityController::class, 'getGovernmentBalance'])->name('api.government.balance');

// Pagina principale
Route::get('/', [CityController::class, 'index'])->name('home');

// Rotte per le città
Route::resource('cities', CityController::class);
Route::post('/city/{city}/increase-production', [CityController::class, 'increaseResourceProduction'])->name('city.increaseProduction');

// Risorse
Route::get('/resources/analysis', [ResourceController::class, 'index'])->name('resources.analysis');
Route::get('/resources/transfer', [ResourceController::class, 'transferView'])->name('resource.transfer');
Route::get('/api/districts/{district}/resources', [ResourceController::class, 'getResources'])->name('api.resources');

// Trasferimenti di risorse
Route::post('/resource-transfer', [ResourceTransferController::class, 'transfer'])->name('resource.transfer');

// Distretti
Route::resource('cities.districts', DistrictController::class);
Route::get('districts/{district}/monitor', [DistrictController::class, 'monitorResources'])->name('districts.monitor');
Route::get('districts/{district}/resources', [DistrictController::class, 'resources'])->name('districts.resources.index');
Route::get('/districts/{district}/recycling-progress', [DistrictController::class, 'showRecyclingProgress'])->name('districts.recycling_progress');
Route::get('/districts/{district}/environmental-impact', [DistrictController::class, 'showEnvironmentalImpact'])->name('districts.environmental_impact');
Route::get('/districts/{district}/agriculture', [AgriculturalController::class, 'show'])->name('districts.agriculture');

// Infrastrutture
Route::post('infrastructures/{infrastructure}/maintain', [InfrastructureController::class, 'maintain'])->name('infrastructures.maintain');
Route::get('infrastructures/{infrastructure}/history', [InfrastructureController::class, 'history'])->name('infrastructures.history');
Route::post('/infrastructures/apply-deterioration', [InfrastructureController::class, 'applyDeterioration'])->name('infrastructures.apply-deterioration');

// Cittadini e Bonus
Route::get('/citizen/recycling-activities', [CitizenController::class, 'showRecyclingActivities'])->name('citizen.recycling_activities');
Route::get('/citizens/{citizen}/bonuses', [BonusController::class, 'showBonuses'])->name('citizens.bonuses');
Route::post('/citizens/{citizen}/claim-voucher', [BonusController::class, 'claimVoucher'])->name('citizens.claimVoucher');
Route::get('/citizens/{citizen}/recycling-progress', [CitizenController::class, 'showRecyclingProgress'])->name('citizens.recyclingProgress');

// Migrazioni
Route::get('/migrations', [MigrationController::class, 'index'])->name('migrations.index');

// Bonus
Route::post('/bonus/recycling/{citizen}/{amountRecycled}', [BonusController::class, 'rewardRecycling']);
Route::post('/bonus/improvement/{citizen}/{activityType}', [BonusController::class, 'rewardCityImprovement']);

// Tipi di Rifiuti e Trattamento
Route::get('/waste/types', [WasteTypeController::class, 'index'])->name('waste_types.index');
Route::post('/recycling/add-points/{citizen}', [RecyclingController::class, 'addPoints']);
Route::get('/waste/treat', [WasteTreatmentController::class, 'treatWaste'])->name('waste.treat');
Route::get('/waste/monitor', [WasteTreatmentController::class, 'monitorResources'])->name('waste.monitor');

// Smaltitori automatici
Route::resource('waste_disposers', AutoWasteDisposerController::class);

// Premi Riciclo
Route::get('/recycling/awards', [RecyclingController::class, 'viewAwards'])->name('recycling.awards');
Route::post('/recycling/assign-awards', [RecyclingController::class, 'assignAnnualAwards'])->name('recycling.assignAwards');

// Agricoltura
Route::get('/agriculture/production', [AgriculturalController::class, 'index'])->name('agricultural.production');

// Produzioni
Route::resource('alcoholics', AlcoholicController::class);

// Politiche
Route::resource('policies', PolicyController::class);

<?php

use App\Models\City;
use App\Models\User;
use App\Models\District;
use App\Models\Resource;
use App\Models\ResourceTransfer;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CitizenController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\InfrastructureController;
use App\Http\Controllers\ResourceTransferController;


Route::get('/api/government/balance', function () {
    $government = User::where('name', 'government')->first();

    // Controlliamo se esiste l'utente government
    if ($government) {
        return response()->json(['balance' => $government->cash]);
    }

    // In caso non esista, restituiamo un errore
    return response()->json(['error' => 'Government not found'], 404);
})->name('api.government.balance');


Route::get('/', [CityController::class, 'index'])->name('home');

Route::resource('cities', CityController::class);
Route::post('/city/{city}/increase-production', [CityController::class, 'increaseResourceProduction'])->name('city.increaseProduction');

// Risorse
Route::get('/resources/analysis', [ResourceController::class, 'index'])->name('resources.analysis');
Route::get('/resources/transfer', [ResourceController::class, 'transferView'])->name('resource.transfer');
Route::get('/api/districts/{district}/resources', [ResourceController::class, 'getResources'])->name('api.resources');
Route::get('/api/resources', function () {
    $city = City::with('districts.resources')->first(); // Carichiamo la città e le risorse dei distretti
    return response()->json($city->districts->map(function ($district) {
        return [
            'district' => $district->name,
            'resources' => $district->resources->map(function ($resource) {
                return [
                    'name' => $resource->name,
                    'quantity' => $resource->quantity,
                    'daily_production' => $resource->daily_production,
                    'unit' => $resource->unit,
                ];
            }),
        ];
    }));
})->name('api.resources');

// Trasferimenti di risorse
Route::get('/resources/transfer', function () {
    $districts = District::all();
    return view('resources.transfer', compact('districts'));
});

Route::post('/resource-transfer', [ResourceTransferController::class, 'transfer'])->name('resource.transfer');

// Distretti
Route::resource('cities.districts', DistrictController::class);
Route::get('districts/{district}/monitor', [DistrictController::class, 'monitorResources'])->name('districts.monitor');
Route::get('districts/{district}/resources', [DistrictController::class, 'resources'])->name('districts.resources.index');
Route::get('/districts/{district}/recycling-progress', [DistrictController::class, 'showRecyclingProgress'])->name('districts.recycling_progress');
Route::get('/districts/{district}/environmental-impact', [DistrictController::class, 'showEnvironmentalImpact'])->name('districts.environmental_impact');

// Infrastrutture
Route::post('infrastructures/{infrastructure}/maintain', [InfrastructureController::class, 'maintain'])->name('infrastructures.maintain');
Route::get('infrastructures/{infrastructure}/history', [InfrastructureController::class, 'history'])->name('infrastructures.history');
Route::post('/infrastructures/apply-deterioration', [InfrastructureController::class, 'applyDeterioration'])->name('infrastructures.apply-deterioration');

Route::get('/citizen/recycling-activities', [CitizenController::class, 'showRecyclingActivities'])->name('citizen.recycling_activities');

Route::get('/migrations', [MigrationController::class, 'index'])->name('migrations.index');

<?php

use App\Models\City;
use App\Models\District;
use App\Models\Resource;
use App\Models\ResourceTransfer;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\InfrastructureController;
use App\Http\Controllers\ResourceTransferController;

Route::get('/', [CityController::class, 'index'])->name('home');

Route::resource('cities', CityController::class);

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

// Infrastrutture
Route::post('infrastructures/{infrastructure}/maintain', [InfrastructureController::class, 'maintain'])->name('infrastructures.maintain');
Route::get('infrastructures/{infrastructure}/history', [InfrastructureController::class, 'history'])->name('infrastructures.history');

<?php

namespace App\Http\Controllers\City;

use App\Models\CLAIR;
use App\Models\City\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $city = City::first();

        // Log dell'accesso alla dashboard e raccolta dei dati principali
        CLAIR::logActivity(
            'C',
            'index',
            'Accesso alla dashboard della città e caricamento dei dati energetici dei distretti',
            ['city_id' => $city->id]
        );

        return view('cities.dashboard', [
            'districtNames' => $city->districts->pluck('name')->toArray(),
            'energyProductionData' => $city->districts->map->calculateDistrictEnergyProduction(),
            // Altri dati da passare ai grafici
        ]);
    }

    public function map()
    {
        $city = City::first();

        // Log dell'accesso alla mappa interattiva della città
        CLAIR::logActivity(
            'C',
            'map',
            'Accesso alla mappa della città con impatti di sicurezza per ciascun distretto',
            ['city_id' => $city->id]
        );

        return view('cities.map', [
            'districts' => $city->districts->map(function ($district) {
                return [
                    'name' => $district->name,
                    'latitude' => $district->latitude,
                    'longitude' => $district->longitude,
                    'safety_level' => $district->calculateDistrictSafetyImpact(),
                    // Altri dati di impatto
                ];
            })
        ]);
    }
}

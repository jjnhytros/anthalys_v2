<?php

namespace App\Http\Controllers\Agricolture;

use App\Models\CLAIR;
use App\Models\City\District;
use App\Models\Agricolture\Season;
use App\Http\Controllers\Controller;

class AgriculturalController extends Controller
{
    public function index()
    {
        // Ottieni la stagione corrente
        $currentSeason = Season::getCurrentSeason();

        // Recupera i distretti e le risorse
        $districts = District::with('resources')->get();

        // Registra l'attività di visualizzazione della produzione agricola
        CLAIR::logActivity(
            'A', // Tipo di attività per Agricultural
            'index',
            'Visualizzazione della produzione agricola',
            ['season' => $currentSeason->name, 'district_count' => $districts->count()]
        );

        return view('agriculture.production', compact('currentSeason', 'districts'));
    }

    public function show(District $district)
    {
        // Otteniamo le risorse agricole per il distretto
        $resources = $district->agriculturalResources;

        // Registra l'attività di monitoraggio delle risorse agricole
        CLAIR::logActivity(
            'A', // Tipo di attività per Agricultural
            'show',
            'Monitoraggio delle risorse agricole nel distretto',
            ['district_id' => $district->id, 'resource_count' => $resources->count()]
        );

        return view('agriculture.monitor', compact('district', 'resources'));
    }
}

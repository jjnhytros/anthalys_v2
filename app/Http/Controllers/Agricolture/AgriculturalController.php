<?php

namespace App\Http\Controllers\Agricolture;

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

        return view('agriculture.production', compact('currentSeason', 'districts'));
    }

    public function show(District $district)
    {
        // Otteniamo le risorse agricole per il distretto
        $resources = $district->agriculturalResources;

        return view('agriculture.monitor', compact('district', 'resources'));
    }
}

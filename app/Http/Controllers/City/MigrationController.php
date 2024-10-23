<?php

namespace App\Http\Controllers\City;

use App\Models\City\District;
use App\Models\City\Migration;
use App\Http\Controllers\Controller;

class MigrationController extends Controller
{
    public function index()
    {
        $migrations = Migration::with('fromDistrict', 'toDistrict')->get();
        $problematicDistricts = District::whereHas('resources', function ($query) {
            $query->where('quantity', '<', 500);
        })->orWhereHas('infrastructures', function ($query) {
            $query->where('condition', '<', 0.5);
        })->get();

        return view('migrations.index', compact('migrations', 'problematicDistricts'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;

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

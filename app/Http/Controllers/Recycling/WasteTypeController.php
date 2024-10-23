<?php

namespace App\Http\Controllers\Recycling;

use App\Models\Recycling\WasteType;
use App\Http\Controllers\Controller;

class WasteTypeController extends Controller
{
    public function index()
    {
        $wasteTypes = WasteType::all(); // Recupera tutti i tipi di rifiuti
        return view('waste.types.index', compact('wasteTypes'));
    }
}

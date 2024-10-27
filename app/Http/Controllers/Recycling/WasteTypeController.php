<?php

namespace App\Http\Controllers\Recycling;

use App\Models\CLAIR;
use App\Models\Recycling\WasteType;
use App\Http\Controllers\Controller;

class WasteTypeController extends Controller
{
    public function index()
    {
        $wasteTypes = WasteType::all(); // Recupera tutti i tipi di rifiuti

        // Log dell'attività di visualizzazione dei tipi di rifiuti
        CLAIR::logActivity('C', 'index', 'Visualizzazione dei tipi di rifiuti', [
            'total_waste_types' => $wasteTypes->count()
        ]);

        return view('waste.types.index', compact('wasteTypes'));
    }
}

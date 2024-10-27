<?php

namespace App\Http\Controllers\Recycling;

use App\Models\CLAIR;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Recycling\AutoWasteDisposer;

class AutoWasteDisposerController extends Controller
{
    public function index()
    {
        // Mostra gli smaltitori per il cittadino loggato
        $citizen = Auth::user()->citizen;
        $disposers = $citizen->autoWasteDisposers;

        // Log dell'attività di visualizzazione della lista degli smaltitori
        CLAIR::logActivity('C', 'index', 'Visualizzazione lista smaltitori automatici per il cittadino', [
            'citizen_id' => $citizen->id,
            'disposers_count' => $disposers->count(),
        ]);

        return view('waste.disposers.index', compact('disposers'));
    }

    public function store(Request $request)
    {
        // Permetti al cittadino di acquistare uno smaltitore automatico
        $citizen = Auth::user()->citizen;

        $disposer = AutoWasteDisposer::create([
            'type' => $request->type,
            'efficiency' => $request->efficiency,
            'citizen_id' => $citizen->id,
        ]);

        // Log dell'attività di acquisto di uno smaltitore automatico
        CLAIR::logActivity('A', 'store', 'Acquisto di uno smaltitore automatico', [
            'citizen_id' => $citizen->id,
            'disposer_id' => $disposer->id,
            'type' => $request->type,
            'efficiency' => $request->efficiency,
        ]);

        return redirect()->route('waste.disposers.index')->with('success', 'Smaltitore acquistato con successo!');
    }
}

<?php

namespace App\Http\Controllers\Recycling;

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

        return view('waste.disposers.index', compact('disposers'));
    }

    public function store(Request $request)
    {
        // Permetti al cittadino di acquistare uno smaltitore automatico
        $citizen = Auth::user()->citizen;

        AutoWasteDisposer::create([
            'type' => $request->type,
            'efficiency' => $request->efficiency,
            'citizen_id' => $citizen->id,
        ]);

        return redirect()->route('waste.disposers.index')->with('success', 'Smaltitore acquistato con successo!');
    }
}

<?php

namespace App\Http\Controllers\Market;

use App\Models\CLAIR;
use App\Models\City\LocalMarket;
use App\Http\Controllers\Controller;

class StallController extends Controller
{
    public function index(LocalMarket $market)
    {
        $stalls = $market->stalls()->with(['owner', 'products'])->get();

        // Registra l'attività utilizzando C.L.A.I.R.
        CLAIR::logActivity('C', 'index', 'Visualizzazione delle bancarelle del mercato', [
            'market_id' => $market->id,
            'stall_count' => $stalls->count(),
        ]);

        return view('markets.stalls.index', compact('market', 'stalls'));
    }
}

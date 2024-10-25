<?php

namespace App\Http\Controllers\Market;

use App\Models\City\LocalMarket;
use App\Http\Controllers\Controller;

class StallController extends Controller
{
    public function index(LocalMarket $market)
    {
        $stalls = $market->stalls()->with(['owner', 'products'])->get();
        return view('markets.stalls.index', compact('market', 'stalls'));
    }
}

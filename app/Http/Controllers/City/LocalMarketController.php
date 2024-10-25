<?php

namespace App\Http\Controllers\City;

use Illuminate\Http\Request;
use App\Models\City\LocalMarket;
use App\Http\Controllers\Controller;
use App\Models\Market\MarketProduct;

class LocalMarketController extends Controller
{
    public function index()
    {
        $markets = LocalMarket::all();
        return view('markets.index', compact('markets'));
    }

    public function create()
    {
        return view('markets.create');
    }

    public function store(Request $request)
    {
        LocalMarket::create($request->all());
        return redirect()->route('markets.index');
    }

    public function show(LocalMarket $market)
    {
        return view('markets.show', compact('market'));
    }

    public function edit(LocalMarket $market)
    {
        return view('markets.edit', compact('market'));
    }

    public function update(Request $request, LocalMarket $market)
    {
        $market->update($request->all());
        return redirect()->route('markets.index');
    }

    public function destroy(LocalMarket $market)
    {
        $market->delete();
        return redirect()->route('markets.index');
    }

    public function inventory()
    {
        $products = MarketProduct::all();
        return view('markets.inventory', compact('products'));
    }

    public function pricing()
    {
        // Recupera tutti i prodotti disponibili nel mercato locale
        $products = MarketProduct::all();

        // Passa i prodotti alla view della dashboard dei prezzi
        return view('markets.pricing', compact('products'));
    }
}

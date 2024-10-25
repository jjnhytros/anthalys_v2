<?php

namespace App\Http\Controllers\Market;

use Illuminate\Http\Request;
use App\Models\City\LocalMarket;
use App\Http\Controllers\Controller;
use App\Models\Market\MarketProduct;

class MarketProductController extends Controller
{
    public function index()
    {
        $products = MarketProduct::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $markets = LocalMarket::all();
        return view('products.create', compact('markets'));
    }

    public function store(Request $request)
    {
        MarketProduct::create($request->all());
        return redirect()->route('products.index');
    }

    public function show(MarketProduct $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(MarketProduct $product)
    {
        $markets = LocalMarket::all();
        return view('products.edit', compact('product', 'markets'));
    }

    public function update(Request $request, MarketProduct $product)
    {
        $product->update($request->all());
        return redirect()->route('products.index');
    }

    public function destroy(MarketProduct $product)
    {
        $product->delete();
        return redirect()->route('products.index');
    }
}

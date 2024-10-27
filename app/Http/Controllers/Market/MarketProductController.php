<?php

namespace App\Http\Controllers\Market;

use App\Models\CLAIR;
use Illuminate\Http\Request;
use App\Models\City\LocalMarket;
use App\Http\Controllers\Controller;
use App\Models\Market\MarketProduct;

class MarketProductController extends Controller
{
    public function index()
    {
        // Log dell'attività di visualizzazione della lista dei prodotti di mercato
        CLAIR::logActivity(
            'C', // Categoria per commercio/mercato
            'index',
            'Accesso alla lista dei prodotti di mercato',
            []
        );

        $products = MarketProduct::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        // Log dell'attività di accesso alla creazione di un nuovo prodotto di mercato
        CLAIR::logActivity(
            'C',
            'create',
            'Accesso alla creazione di un nuovo prodotto di mercato',
            []
        );

        $markets = LocalMarket::all();
        return view('products.create', compact('markets'));
    }

    public function store(Request $request)
    {
        MarketProduct::create($request->all());

        // Log dell'attività di salvataggio di un nuovo prodotto di mercato
        CLAIR::logActivity(
            'C',
            'store',
            'Creazione di un nuovo prodotto di mercato',
            ['data' => $request->all()]
        );

        return redirect()->route('products.index');
    }

    public function show(MarketProduct $product)
    {
        // Log dell'attività di visualizzazione di un prodotto specifico
        CLAIR::logActivity(
            'C',
            'show',
            'Visualizzazione dettagli del prodotto di mercato',
            ['product_id' => $product->id]
        );

        return view('products.show', compact('product'));
    }

    public function edit(MarketProduct $product)
    {
        // Log dell'attività di accesso alla modifica di un prodotto di mercato
        CLAIR::logActivity(
            'C',
            'edit',
            'Accesso alla modifica del prodotto di mercato',
            ['product_id' => $product->id]
        );

        $markets = LocalMarket::all();
        return view('products.edit', compact('product', 'markets'));
    }

    public function update(Request $request, MarketProduct $product)
    {
        $product->update($request->all());

        // Log dell'attività di aggiornamento dei dettagli del prodotto di mercato
        CLAIR::logActivity(
            'C',
            'update',
            'Aggiornamento dei dettagli del prodotto di mercato',
            ['product_id' => $product->id, 'data' => $request->all()]
        );

        return redirect()->route('products.index');
    }

    public function destroy(MarketProduct $product)
    {
        $product->delete();

        // Log dell'attività di eliminazione di un prodotto di mercato
        CLAIR::logActivity(
            'C',
            'destroy',
            'Eliminazione del prodotto di mercato',
            ['product_id' => $product->id]
        );

        return redirect()->route('products.index');
    }
}

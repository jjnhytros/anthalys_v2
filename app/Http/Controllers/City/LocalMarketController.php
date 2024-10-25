<?php

namespace App\Http\Controllers\City;

use App\Models\City\Citizen;
use Illuminate\Http\Request;
use App\Models\City\LocalMarket;
use App\Http\Controllers\Controller;
use App\Models\Market\MarketProduct;
use App\Models\MegaWarehouse\Warehouse;
use App\Models\MegaWarehouse\WarehouseTransaction;

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

    public function purchaseFromWarehouse(Request $request, $supplierId)
    {
        $supplier = Citizen::findOrFail($supplierId);
        $product = Warehouse::where('product_type', $request->product_type)->first();

        if ($product && $product->quantity >= $request->quantity) {
            // Riduci la quantità dal magazzino e registra la transazione
            $product->quantity -= $request->quantity;
            $product->save();

            WarehouseTransaction::create([
                'product_id' => $product->id,
                'supplier_id' => $supplier->id,
                'quantity' => $request->quantity,
                'transaction_type' => 'purchase',
                'date' => now(),
            ]);

            return response()->json(['message' => 'Acquisto dal magazzino completato.']);
        }

        return response()->json(['error' => 'Scorte insufficienti.'], 400);
    }

    public function sellToMarket(Request $request, $vendorId)
    {
        $vendor = Citizen::findOrFail($vendorId);
        $marketProduct = MarketProduct::where('name', $request->product_name)->first();

        if ($marketProduct) {
            $marketProduct->quantity += $request->quantity;
            $marketProduct->save();

            return response()->json(['message' => 'Vendita al mercato completata.']);
        }

        return response()->json(['error' => 'Prodotto non trovato nel mercato.'], 404);
    }

    public function checkStock()
    {
        $lowStockItems = MarketProduct::where('quantity', '<', 'min_quantity')->get();
        return response()->json($lowStockItems);
    }
}

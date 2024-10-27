<?php

namespace App\Http\Controllers\City;

use App\Models\CLAIR;
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

        // Log dell'attività di visualizzazione dei mercati locali
        CLAIR::logActivity(
            'C',
            'index',
            'Visualizzazione dell\'elenco dei mercati locali',
            ['market_count' => $markets->count()]
        );

        return view('markets.index', compact('markets'));
    }

    public function create()
    {
        // Log dell'attività di creazione di un nuovo mercato
        CLAIR::logActivity(
            'C',
            'create',
            'Apertura del modulo di creazione del mercato locale',
            []
        );

        return view('markets.create');
    }

    public function store(Request $request)
    {
        $market = LocalMarket::create($request->all());

        // Log dell'attività di salvataggio del nuovo mercato
        CLAIR::logActivity(
            'A',
            'store',
            'Salvataggio del nuovo mercato locale',
            ['market_id' => $market->id]
        );

        return redirect()->route('markets.index');
    }

    public function show(LocalMarket $market)
    {
        // Log dell'attività di visualizzazione del mercato
        CLAIR::logActivity(
            'C',
            'show',
            'Visualizzazione dei dettagli del mercato locale',
            ['market_id' => $market->id]
        );

        return view('markets.show', compact('market'));
    }

    public function edit(LocalMarket $market)
    {
        // Log dell'attività di modifica del mercato
        CLAIR::logActivity(
            'C',
            'edit',
            'Apertura del modulo di modifica per il mercato locale',
            ['market_id' => $market->id]
        );

        return view('markets.edit', compact('market'));
    }

    public function update(Request $request, LocalMarket $market)
    {
        $market->update($request->all());

        // Log dell'attività di aggiornamento del mercato
        CLAIR::logActivity(
            'R',
            'update',
            'Aggiornamento dei dettagli del mercato locale',
            ['market_id' => $market->id]
        );

        return redirect()->route('markets.index');
    }

    public function destroy(LocalMarket $market)
    {
        $marketId = $market->id;
        $market->delete();

        // Log dell'attività di eliminazione del mercato
        CLAIR::logActivity(
            'R',
            'destroy',
            'Eliminazione del mercato locale',
            ['market_id' => $marketId]
        );

        return redirect()->route('markets.index');
    }

    public function inventory()
    {
        $products = MarketProduct::all();

        // Log dell'attività di visualizzazione dell'inventario
        CLAIR::logActivity(
            'C',
            'inventory',
            'Visualizzazione dell\'inventario del mercato',
            ['product_count' => $products->count()]
        );

        return view('markets.inventory', compact('products'));
    }

    public function pricing()
    {
        $products = MarketProduct::all();

        // Log dell'attività di visualizzazione della dashboard dei prezzi
        CLAIR::logActivity(
            'C',
            'pricing',
            'Visualizzazione della dashboard dei prezzi di mercato',
            ['product_count' => $products->count()]
        );

        return view('markets.pricing', compact('products'));
    }

    public function purchaseFromWarehouse(Request $request, $supplierId)
    {
        $supplier = Citizen::findOrFail($supplierId);
        $product = Warehouse::where('product_type', $request->product_type)->first();

        if ($product && $product->quantity >= $request->quantity) {
            $product->quantity -= $request->quantity;
            $product->save();

            WarehouseTransaction::create([
                'product_id' => $product->id,
                'supplier_id' => $supplier->id,
                'quantity' => $request->quantity,
                'transaction_type' => 'purchase',
                'date' => now(),
            ]);

            // Log dell'acquisto dal magazzino
            CLAIR::logActivity(
                'A',
                'purchaseFromWarehouse',
                'Acquisto di prodotti dal magazzino',
                ['supplier_id' => $supplierId, 'product_type' => $request->product_type, 'quantity' => $request->quantity]
            );

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

            // Log della vendita al mercato
            CLAIR::logActivity(
                'A',
                'sellToMarket',
                'Vendita di prodotti al mercato',
                ['vendor_id' => $vendorId, 'product_name' => $request->product_name, 'quantity' => $request->quantity]
            );

            return response()->json(['message' => 'Vendita al mercato completata.']);
        }

        return response()->json(['error' => 'Prodotto non trovato nel mercato.'], 404);
    }

    public function checkStock()
    {
        $lowStockItems = MarketProduct::where('quantity', '<', 'min_quantity')->get();

        // Log del controllo delle scorte
        CLAIR::logActivity(
            'C',
            'checkStock',
            'Controllo delle scorte di mercato con livello basso',
            ['low_stock_count' => $lowStockItems->count()]
        );

        return response()->json($lowStockItems);
    }
}

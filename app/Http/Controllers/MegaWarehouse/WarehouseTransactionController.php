<?php

namespace App\Http\Controllers\MegaWarehouse;

use App\Models\CLAIR;
use App\Models\City\Citizen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Market\MarketProduct;
use App\Models\MegaWarehouse\Warehouse;
use App\Models\MegaWarehouse\SupplierPayment;
use App\Models\MegaWarehouse\WarehouseTransaction;

class WarehouseTransactionController extends Controller
{
    public function index()
    {
        $transactions = WarehouseTransaction::with('citizen')->get();

        // Log dell'attività di visualizzazione dell'indice delle transazioni
        CLAIR::logActivity('C', 'index', 'Visualizzazione dell\'elenco delle transazioni');

        return view('warehouse.transactions.index', compact('transactions'));
    }

    public function create(Request $request)
    {
        // Validazione dei dati
        $request->validate([
            'citizen_id' => 'required|exists:citizens,id',
            'product_type' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'transaction_type' => 'required|in:purchase,sale',
        ]);

        $citizen = Citizen::findOrFail($request->citizen_id);
        $warehouse = Warehouse::where('product_type', $request->product_type)->first();

        if (!$warehouse || $warehouse->quantity < $request->quantity) {
            return response()->json(['error' => 'Stock insufficiente.'], 400);
        }

        $transaction = WarehouseTransaction::create([
            'citizen_id' => $citizen->id,
            'product_type' => $request->product_type,
            'quantity' => $request->quantity,
            'transaction_type' => $request->transaction_type,
        ]);

        // Aggiorna le quantità nel magazzino e logga l'attività
        if ($transaction->transaction_type === 'purchase') {
            $warehouse->quantity -= $transaction->quantity;
            CLAIR::logActivity('I', 'purchase', 'Transazione di acquisto completata', [
                'citizen_id' => $citizen->id,
                'product_type' => $request->product_type,
                'quantity' => $request->quantity
            ]);
        } else {
            $warehouse->quantity += $transaction->quantity;
            CLAIR::logActivity('I', 'sale', 'Transazione di vendita completata', [
                'citizen_id' => $citizen->id,
                'product_type' => $request->product_type,
                'quantity' => $request->quantity
            ]);

            $totalCost = $transaction->quantity * $warehouse->product->purchase_price;
            SupplierPayment::create([
                'supplier_id' => $citizen->id,
                'product_id' => $warehouse->id,
                'amount' => $totalCost,
                'payment_date' => now(),
            ]);
        }

        $warehouse->save();
        return response()->json(['message' => 'Transazione completata con successo.', 'transaction' => $transaction]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'citizen_id' => 'required|exists:citizens,id',
            'product_id' => 'required|exists:market_products,id',
            'quantity' => 'required|integer|min:1',
            'transaction_type' => 'required|in:purchase,sale',
        ]);

        $citizen = Citizen::findOrFail($request->citizen_id);
        $product = MarketProduct::findOrFail($request->product_id);
        $warehouse = Warehouse::where('product_type', $product->type)->first();

        if (!$warehouse || $warehouse->quantity < $request->quantity) {
            return response()->json(['error' => 'Stock insufficiente.'], 400);
        }

        $transaction = WarehouseTransaction::create([
            'citizen_id' => $citizen->id,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'transaction_type' => $request->transaction_type,
        ]);

        // Aggiorna la quantità del magazzino e logga l'attività
        if ($transaction->transaction_type === 'purchase') {
            $warehouse->quantity -= $transaction->quantity;
            CLAIR::logActivity('A', 'store_purchase', 'Acquisto completato', [
                'citizen_id' => $citizen->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        } else {
            $warehouse->quantity += $transaction->quantity;
            CLAIR::logActivity('A', 'store_sale', 'Vendita completata', [
                'citizen_id' => $citizen->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }

        $warehouse->save();
        return response()->json(['message' => 'Transazione completata con successo.', 'transaction' => $transaction]);
    }

    public function show($id)
    {
        $transaction = WarehouseTransaction::findOrFail($id);

        // Log dell'attività per la visualizzazione dei dettagli della transazione
        CLAIR::logActivity('R', 'show', 'Visualizzazione dei dettagli della transazione', [
            'transaction_id' => $id
        ]);

        return view('warehouse.transactions.show', compact('transaction'));
    }
}

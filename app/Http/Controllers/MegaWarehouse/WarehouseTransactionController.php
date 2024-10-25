<?php

namespace App\Http\Controllers\MegaWarehouse;

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
        // Recupera tutte le transazioni
        $transactions = WarehouseTransaction::with('citizen')->get();
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

        // Trova il cittadino e il prodotto
        $citizen = Citizen::findOrFail($request->citizen_id);
        $warehouse = Warehouse::where('product_type', $request->product_type)->first();

        if (!$warehouse || $warehouse->quantity < $request->quantity) {
            return response()->json(['error' => 'Stock insufficiente.'], 400);
        }

        // Esegui la transazione
        $transaction = WarehouseTransaction::create([
            'citizen_id' => $citizen->id,
            'product_type' => $request->product_type,
            'quantity' => $request->quantity,
            'transaction_type' => $request->transaction_type,
        ]);

        // Aggiorna le quantità nel magazzino
        if ($transaction->transaction_type === 'purchase') {
            $warehouse->quantity -= $transaction->quantity;
            $warehouse->save();
        } else {
            $warehouse->quantity += $transaction->quantity;
            $warehouse->save();

            // Calcola il pagamento per il fornitore
            $totalCost = $transaction->quantity * $warehouse->product->purchase_price; // Assumendo che il prezzo d'acquisto sia un attributo del modello Warehouse

            // Registra il pagamento al fornitore
            SupplierPayment::create([
                'supplier_id' => $citizen->id, // Supponendo che il cittadino sia anche un fornitore
                'product_id' => $warehouse->id,
                'amount' => $totalCost,
                'payment_date' => now(),
            ]);
        }

        return response()->json(['message' => 'Transazione completata con successo.', 'transaction' => $transaction]);
    }

    public function store(Request $request)
    {
        // Validazione dei dati
        $request->validate([
            'citizen_id' => 'required|exists:citizens,id',
            'product_id' => 'required|exists:market_products,id',
            'quantity' => 'required|integer|min:1',
            'transaction_type' => 'required|in:purchase,sale',
        ]);

        // Trova il cittadino e il prodotto
        $citizen = Citizen::findOrFail($request->citizen_id);
        $product = MarketProduct::findOrFail($request->product_id);
        $warehouse = Warehouse::where('product_type', $product->type)->first();

        if (!$warehouse || $warehouse->quantity < $request->quantity) {
            return response()->json(['error' => 'Stock insufficiente.'], 400);
        }

        // Esegui la transazione
        $transaction = WarehouseTransaction::create([
            'citizen_id' => $citizen->id,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'transaction_type' => $request->transaction_type,
        ]);

        // Aggiorna le quantità nel magazzino
        if ($transaction->transaction_type === 'purchase') {
            $warehouse->quantity -= $transaction->quantity;
        } else {
            $warehouse->quantity += $transaction->quantity;
        }

        $warehouse->save();

        return response()->json(['message' => 'Transazione completata con successo.', 'transaction' => $transaction]);
    }

    public function show($id)
    {
        $transaction = WarehouseTransaction::findOrFail($id);
        return view('warehouse.transactions.show', compact('transaction'));
    }
}

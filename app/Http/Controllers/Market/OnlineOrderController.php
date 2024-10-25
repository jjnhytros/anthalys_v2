<?php

namespace App\Http\Controllers\Market;

use App\Models\City\Message;
use Illuminate\Http\Request;
use App\Models\City\LoyaltyPoint;
use App\Models\Market\OnlineOrder;
use App\Http\Controllers\Controller;
use App\Models\Market\MarketProduct;
use Illuminate\Support\Facades\Auth;

class OnlineOrderController extends Controller
{
    public function index()
    {
        $orders = OnlineOrder::where('citizen_id', Auth::user()->citizen->id())->with('product')->get();
        return view('markets.orders.index', compact('orders'));
    }

    public function create()
    {
        return view('markets.orders.create');
    }

    public function store(Request $request)
    {
        $product = MarketProduct::findOrFail($request->product_id);
        $quantity = $request->quantity;

        if ($product->quantity >= $quantity) {
            // Crea l'ordine
            OnlineOrder::create([
                'citizen_id' => Auth::user()->citizen->id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
                'status' => 'pending',
            ]);

            // Aggiorna la quantità del prodotto
            $product->quantity -= $quantity;
            $product->save();

            // Aggiorna domanda e prezzo in base alle scorte e domanda
            $product->updateDemandAndPrice();

            // Notifica scorte basse se necessario
            $this->notifyLowStock($product);

            // Aggiungi punti fedeltà al cittadino
            $this->addLoyaltyPoints(Auth::user()->citizen->id, $product->price * $quantity);

            return redirect()->back()->with('success', 'Ordine completato con successo!');
        } else {
            return redirect()->back()->with('error', 'Quantità non disponibile.');
        }
    }

    public function show(OnlineOrder $order)
    {
        return view('markets.orders.show', compact('order'));
    }

    public function edit(OnlineOrder $order)
    {
        return view('markets.orders.edit', compact('order'));
    }

    public function update(Request $request, OnlineOrder $order)
    {
        $order->update($request->all());
        return redirect()->route('orders.index');
    }

    public function destroy(OnlineOrder $order)
    {
        $order->delete();
        return redirect()->route('orders.index');
    }

    public function handleOrder(OnlineOrder $order)
    {
        $product = MarketProduct::find($order->product_id);

        if ($product->quantity < $order->quantity) {
            return response()->json(['message' => 'Quantità non sufficiente'], 400);
        }

        // Riduci la quantità disponibile
        $product->quantity -= $order->quantity;
        $product->save();

        // Aggiorna lo stato dell'ordine
        $order->update(['status' => 'completed']);

        return response()->json(['message' => 'Ordine completato con successo!'], 200);
    }

    public function confirm(Request $request, $id)
    {
        $order = OnlineOrder::findOrFail($id);

        // Verifica che l'ordine non sia già stato confermato o cancellato
        if ($order->isConfirmed() || $order->isCanceled()) {
            return redirect()->back()->with('error', 'Questo ordine non può essere confermato.');
        }

        // Conferma l'ordine
        $order->confirm();
        return redirect()->back()->with('success', 'Ordine confermato con successo!');
    }

    public function cancel(Request $request, $id)
    {
        $order = OnlineOrder::findOrFail($id);

        // Verifica che l'ordine non sia già stato confermato o cancellato
        if ($order->isConfirmed() || $order->isCanceled()) {
            return redirect()->back()->with('error', 'Questo ordine non può essere cancellato.');
        }

        // Cancella l'ordine
        $order->cancel();
        return redirect()->back()->with('success', 'Ordine cancellato con successo!');
    }

    public function history()
    {
        $orders = OnlineOrder::where('citizen_id', Auth::user()->citizen->id)->orderBy('created_at', 'desc')->get();
        return view('orders.history', compact('orders'));
    }

    protected function addLoyaltyPoints($citizenId, $orderTotal)
    {
        // Calcola i punti (1 punto per ogni 12 AA spesi)
        $points = floor($orderTotal / 12);

        // Trova o crea i punti fedeltà per il cittadino
        $loyalty = LoyaltyPoint::firstOrCreate(['citizen_id' => $citizenId]);

        // Aggiorna i punti del cittadino
        $loyalty->points += $points;
        $loyalty->save();
    }

    protected function notifyLowStock($product)
    {
        if ($product->quantity < 10) {
            Message::create([
                'sender_id' => 2, // ID del governo o amministratore
                'recipient_id' => $product->market->owner_id, // Proprietario del mercato
                'subject' => 'Scorte Basse',
                'body' => "Il prodotto {$product->name} ha scorte basse ({$product->quantity} unità rimanenti).",
                'is_read' => false,
                'is_archived' => false,
                'is_notification' => true,
                'created_at' => now(),
            ]);
        }
    }
}

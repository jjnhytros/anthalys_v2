<?php

namespace App\Http\Controllers\Market;

use App\Models\CLAIR;
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

        CLAIR::logActivity('C', 'index', 'Visualizzazione degli ordini online', ['citizen_id' => Auth::user()->citizen->id]);

        return view('markets.orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $product = MarketProduct::findOrFail($request->product_id);
        $quantity = $request->quantity;

        if ($product->quantity >= $quantity) {
            OnlineOrder::create([
                'citizen_id' => Auth::user()->citizen->id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
                'status' => 'pending',
            ]);

            $product->quantity -= $quantity;
            $product->save();
            $product->updateDemandAndPrice();

            CLAIR::logActivity('A', 'store', 'Creazione di un nuovo ordine', [
                'citizen_id' => Auth::user()->citizen->id(),
                'product_id' => $product->id,
                'quantity' => $quantity
            ]);

            $this->notifyLowStock($product);
            $this->addLoyaltyPoints(Auth::user()->citizen->id, $product->price * $quantity);

            return redirect()->back()->with('success', 'Ordine completato con successo!');
        } else {
            return redirect()->back()->with('error', 'Quantità non disponibile.');
        }
    }

    public function confirm(Request $request, $id)
    {
        $order = OnlineOrder::findOrFail($id);

        if ($order->isConfirmed() || $order->isCanceled()) {
            return redirect()->back()->with('error', 'Questo ordine non può essere confermato.');
        }

        $order->confirm();

        CLAIR::logActivity('R', 'confirm', 'Conferma ordine', ['order_id' => $id]);

        return redirect()->back()->with('success', 'Ordine confermato con successo!');
    }

    public function cancel(Request $request, $id)
    {
        $order = OnlineOrder::findOrFail($id);

        if ($order->isConfirmed() || $order->isCanceled()) {
            return redirect()->back()->with('error', 'Questo ordine non può essere cancellato.');
        }

        $order->cancel();

        CLAIR::logActivity('R', 'cancel', 'Cancellazione ordine', ['order_id' => $id]);

        return redirect()->back()->with('success', 'Ordine cancellato con successo!');
    }

    protected function addLoyaltyPoints($citizenId, $orderTotal)
    {
        $points = floor($orderTotal / 12);
        $loyalty = LoyaltyPoint::firstOrCreate(['citizen_id' => $citizenId]);

        $loyalty->points += $points;
        $loyalty->save();

        CLAIR::logActivity('L', 'addLoyaltyPoints', 'Assegnazione punti fedeltà', [
            'citizen_id' => $citizenId,
            'points' => $points
        ]);
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

            CLAIR::logActivity('I', 'notifyLowStock', 'Notifica di scorte basse', [
                'product_id' => $product->id,
                'quantity' => $product->quantity
            ]);
        }
    }
}

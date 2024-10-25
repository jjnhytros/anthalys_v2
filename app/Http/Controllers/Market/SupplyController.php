<?php

namespace App\Http\Controllers\Market;

use App\Models\City\Message;
use App\Models\Market\Stall;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Market\MarketProduct;

class SupplyController extends Controller
{
    public function create(Stall $stall)
    {
        $products = MarketProduct::where('stall_id', $stall->id)->get();
        return view('markets.supplies.create', compact('products', 'stall'));
    }

    public function store(Request $request)
    {
        $product = MarketProduct::findOrFail($request->product_id);
        $supplyQuantity = $request->quantity;
        $purchasePrice = $request->purchase_price;

        // Aggiungi la quantità rifornita alle scorte
        $product->quantity += $supplyQuantity;
        $product->purchase_price = $purchasePrice;

        // Imposta un margine di profitto del 24% sul prezzo di acquisto per calcolare il prezzo di vendita
        $product->price = $purchasePrice * 1.24;
        $product->save();

        // Notifica scorte basse se necessario
        $this->notifyLowStock($product);

        return redirect()->back()->with('success', 'Prodotto rifornito con successo!');
    }

    protected function notifyLowStock($product)
    {
        if ($product->quantity < 10) {
            Message::create([
                'sender_id' => 2, // ID del governo o amministratore
                'recipient_id' => $product->stall->owner_id, // Proprietario della bancarella
                'subject' => 'Scorte Basse',
                'body' => "Il prodotto {$product->name} ha scorte basse ({$product->quantity} unità rimanenti) nella tua bancarella.",
                'is_read' => false,
                'is_archived' => false,
                'is_notification' => true,
                'created_at' => now(),
            ]);
        }
    }
}

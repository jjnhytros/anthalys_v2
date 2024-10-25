<?php

namespace App\Jobs\MegaWarehouse;

use App\Models\City\Message;
use Illuminate\Bus\Queueable;
use App\Models\Market\MarketProduct;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CheckAndRestockProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $productsToRestock = MarketProduct::whereColumn('quantity', '<', 'min_stock_level')->get();

        foreach ($productsToRestock as $product) {
            $product->restock();

            // Notifica per rifornimento
            // Possiamo creare una notifica per i manager del MegaWarehouse qui, come esempio:
            Message::create([
                'sender_id' => 2, // ID di sistema
                'recipient_id' => 1, // ID manager
                'subject' => 'Rifornimento Automatico Completato',
                'body' => "Il prodotto {$product->name} è stato rifornito con {$product->reorder_amount} unità.",
                'is_read' => false,
                'is_archived' => false,
                'is_notification' => true,
                'created_at' => now(),
            ]);
        }
    }
}

<?php

namespace App\Jobs\MegaWarehouse;

use App\Models\City\Message;
use App\Models\City\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Queue\SerializesModels;
use App\Models\MegaWarehouse\Warehouse;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MonitorExpiringProductsJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $now = Carbon::now();
        $expiringSoon = $now->copy()->addDays(24);
        $donationThreshold = $now->copy()->addDays(18);

        $products = Warehouse::where('expiry_date', '<=', $expiringSoon)->where('status', '!=', 'expired')->get();

        foreach ($products as $product) {
            if ($product->expiry_date <= $donationThreshold && !$product->is_donated) {
                Donation::create([
                    'product_id' => $product->id,
                    'quantity' => $product->quantity,
                    'donation_date' => $now,
                ]);
                $product->update(['status' => 'donated', 'is_donated' => true]);
                $this->sendNotification($product, "Prodotto donato a causa di mancata vendita.");
            } else {
                $product->update(['status' => 'expiring_soon']);
                $this->sendNotification($product, "Prodotto in scadenza entro 24 giorni.");
            }
        }
    }

    protected function sendNotification($product, $message)
    {
        Message::create([
            'sender_id' => 2, // ID amministrativo o di sistema
            'recipient_id' => $product->manager_id,
            'subject' => 'Notifica prodotto',
            'body' => $message,
            'is_read' => false,
            'is_archived' => false,
            'is_notification' => true,
        ]);
    }

    protected function donateProduct(Warehouse $product)
    {
        $product->update(['is_donated' => true, 'status' => 'donated']);

        Message::create([
            'sender_id' => 2, // ID amministrativo
            'recipient_id' => $product->manager_id,
            'subject' => 'Prodotto donato',
            'body' => "Il prodotto {$product->name} è stato donato a causa della scadenza imminente.",
            'is_read' => false,
            'is_archived' => false,
            'is_notification' => true,
        ]);
    }
}

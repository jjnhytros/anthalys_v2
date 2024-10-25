<?php

namespace App\Jobs\MegaWarehouse;

use Carbon\Carbon;
use App\Models\City\Message;
use Illuminate\Queue\SerializesModels;
use App\Models\MegaWarehouse\Warehouse;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MonitorPackagedFoodStockJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $now = Carbon::now();
        $expiringSoon = $now->copy()->addDays(30);
        $donationThreshold = $now->copy()->addDays(10);

        $products = Warehouse::where('product_type', 'packaged')
            ->where('expiry_date', '<=', $expiringSoon)
            ->where('status', '!=', 'expired')
            ->get();

        foreach ($products as $product) {
            if ($product->expiry_date <= $donationThreshold && !$product->is_donated) {
                $product->update(['status' => 'pending_donation']);
                $this->sendNotification($product, "Prodotto confezionato non venduto e pronto per la donazione.");
            } else {
                $product->update(['status' => 'expiring_soon']);
                $this->sendNotification($product, "Prodotto confezionato in scadenza entro 30 giorni.");
            }
        }
    }

    protected function sendNotification($product, $message)
    {
        Message::create([
            'sender_id' => 2,
            'recipient_id' => $product->vendor_id,
            'subject' => 'Avviso di Donazione',
            'body' => $message,
            'is_read' => false,
            'is_archived' => false,
            'is_notification' => true,
            'created_at' => now(),
        ]);
    }
}

<?php

namespace App\Jobs\MegaWarehouse;

use App\Models\City\Message;
use App\Events\LowStockDetected;
use Illuminate\Queue\SerializesModels;
use App\Models\MegaWarehouse\Warehouse;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\MegaWarehouse\SupplierPayment;
use App\Models\MegaWarehouse\WarehouseOperationLog;

class MonitorWarehouseStockJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $lowStockItems = Warehouse::whereColumn('quantity', '<', 'min_quantity')->get();

        foreach ($lowStockItems as $stockItem) {
            event(new LowStockDetected($stockItem));

            $this->initiateRestock($stockItem);
            $this->notifyLowStock($stockItem);
        }
    }

    private function initiateRestock(Warehouse $stockItem)
    {
        $supplier = $stockItem->product->supplier;
        if ($supplier && $supplier->hasEnoughStock($stockItem->reorder_quantity)) {
            // Aggiorna la quantità nel magazzino
            $stockItem->quantity += $stockItem->reorder_quantity;
            $stockItem->save();

            // Registra nel log di operazione
            WarehouseOperationLog::create([
                'operation_type' => 'Restock',
                'product_id' => $stockItem->id,
                'quantity' => $stockItem->reorder_quantity,
                'details' => "Rifornimento automatico di {$stockItem->product_type}",
            ]);

            // Genera pagamento al fornitore
            $totalCost = $stockItem->reorder_quantity * $stockItem->product->purchase_price;
            SupplierPayment::create([
                'supplier_id' => $supplier->id,
                'product_id' => $stockItem->id,
                'amount' => $totalCost,
                'payment_date' => now(),
            ]);
        }
    }

    protected function notifyLowStock($stockItem)
    {
        // Creazione di un messaggio di notifica utilizzando Message
        Message::create([
            'sender_id' => 2, // Supponendo che 2 sia l'ID di sistema o del governo
            'recipient_id' => $stockItem->warehouse_manager_id, // Gestore del magazzino
            'subject' => 'Scorte Basse in Magazzino',
            'body' => "Il prodotto '{$stockItem->product_type}' ha raggiunto un livello critico di scorte di {$stockItem->quantity} unità. È stato avviato il rifornimento.",
            'is_read' => false,
            'is_archived' => false,
            'is_notification' => true,
            'created_at' => now(),
        ]);
    }
}

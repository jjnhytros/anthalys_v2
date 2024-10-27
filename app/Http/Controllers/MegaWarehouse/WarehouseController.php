<?php

namespace App\Http\Controllers\MegaWarehouse;

use Carbon\Carbon;
use App\Models\CLAIR;
use App\Models\City\Citizen;
use App\Models\City\Message;
use Illuminate\Http\Request;
use App\Models\City\Donation;
use App\Models\Resource\Resource;
use App\Models\Market\OnlineOrder;
use Illuminate\Support\Facades\DB;
use App\Models\Recycling\WasteType;
use App\Http\Controllers\Controller;
use App\Models\MegaWarehouse\Warehouse;
use App\Models\Agricolture\CompostStorage;
use App\Jobs\MegaWarehouse\DispatchDroneJob;
use App\Models\MegaWarehouse\SupplierPayment;
use App\Models\MegaWarehouse\WarehouseReport;
use App\Models\MegaWarehouse\WarehouseTransaction;
use App\Models\MegaWarehouse\WarehouseOperationLog;

class WarehouseController extends Controller
{
    public function dashboard()
    {
        $products = Warehouse::all();
        $recyclableOrders = OnlineOrder::where('is_recyclable', true)->count();
        $totalEnergyUsage = Warehouse::sum('energy_usage');
        $freshProducts = Warehouse::where('product_type', 'alimentare')
            ->whereDate('expiry_date', '>=', now())
            ->orderBy('expiry_date', 'asc')
            ->get();
        $expiringSoonFresh = Warehouse::where('status', 'expiring_soon')->where('product_type', 'fresh')->get();
        $expiringSoonPackaged = Warehouse::where('status', 'expiring_soon')->where('product_type', 'packaged')->get();
        $pendingDonations = Warehouse::where('status', 'pending_donation')->get();

        // Log dell'attività per la visualizzazione della dashboard
        CLAIR::logActivity('C', 'dashboard', 'Visualizzazione del dashboard del magazzino', [
            'product_count' => $products->count(),
            'total_energy_usage' => $totalEnergyUsage
        ]);

        return view('warehouse.dashboard', compact(
            'products',
            'expiringSoonFresh',
            'expiringSoonPackaged',
            'recyclableOrders',
            'totalEnergyUsage',
            'freshProducts',
            'pendingDonations'
        ), [
            'averageEnergyUsage' => WarehouseReport::averageEnergyUsage(),
            'recyclableOrderPercentage' => WarehouseReport::recyclableOrderPercentage(),
            'totalRevenue' => WarehouseReport::totalRevenue(),
            'totalWasteGenerated' => WarehouseReport::sum('waste_generated'),
        ]);
    }

    public function checkExpiryDates()
    {
        $soonExpiringProducts = Warehouse::where('product_type', 'alimentare')
            ->whereBetween('expiry_date', [now(), now()->addDays(7)])
            ->get();

        foreach ($soonExpiringProducts as $product) {
            Message::create([
                'sender_id' => 1,
                'recipient_id' => $product->manager_id,
                'subject' => 'Avviso di Scadenza Prodotto',
                'body' => "Il prodotto {$product->product_type} scadrà il {$product->expiry_date}. Verificare le scorte e le vendite.",
                'is_read' => false,
                'is_notification' => true,
            ]);

            // Log dell'attività di notifica scadenza
            CLAIR::logActivity('R', 'checkExpiryDates', 'Notifica di scadenza generata per prodotto', [
                'product_id' => $product->id,
                'expiry_date' => $product->expiry_date
            ]);
        }
    }

    public function securityDashboard()
    {
        $operationLogs = WarehouseOperationLog::orderBy('operation_time', 'desc')->paginate(10);

        // Log dell'attività per la visualizzazione del dashboard di sicurezza
        CLAIR::logActivity('I', 'securityDashboard', 'Accesso alla dashboard di sicurezza', []);

        return view('warehouse.security_dashboard', compact('operationLogs'));
    }

    public function donationDashboard()
    {
        $expiringSoonDate = now()->addDays(24);
        $donationThresholdDate = now()->addDays(18);

        $expiringProducts = Warehouse::where('expiry_date', '<=', $expiringSoonDate)
            ->where('status', 'expiring_soon')
            ->get();

        $pendingDonations = Warehouse::where('expiry_date', '<=', $donationThresholdDate)
            ->where('status', 'pending_donation')
            ->get();

        $donatedProducts = Donation::with('product')->paginate(10);

        // Log dell'attività di visualizzazione della dashboard delle donazioni
        CLAIR::logActivity('A', 'donationDashboard', 'Visualizzazione del dashboard delle donazioni', [
            'expiring_products_count' => $expiringProducts->count(),
            'pending_donations_count' => $pendingDonations->count()
        ]);

        return view('warehouse.donation_dashboard', compact('expiringProducts', 'pendingDonations', 'donatedProducts'));
    }

    public function processOrder(Request $request, $orderId)
    {
        $order = OnlineOrder::findOrFail($orderId);
        $product = Warehouse::where('product_type', $order->product->type)
            ->where('quantity', '>', 0)
            ->first();

        if ($product && $product->quantity >= $order->quantity) {
            $product->quantity -= $order->quantity;
            $product->save();

            $this->dispatchDelivery($order);
            $order->update(['status' => 'in_delivery']);

            // Log dell'attività di elaborazione ordine
            CLAIR::logActivity('C', 'processOrder', 'Elaborazione dell\'ordine per consegna', [
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity_processed' => $order->quantity
            ]);

            return response()->json(['message' => 'Ordine processato e consegna avviata.']);
        } else {
            return response()->json(['error' => 'Prodotto non disponibile.'], 400);
        }
    }

    public function initiateManualRestock(Warehouse $stockItem)
    {
        $supplier = $stockItem->product->supplier;

        if ($supplier && $supplier->hasEnoughStock($stockItem->reorder_quantity)) {
            $stockItem->quantity += $stockItem->reorder_quantity;
            $stockItem->save();

            WarehouseOperationLog::create([
                'operation_type' => 'Restock',
                'product_id' => $stockItem->id,
                'quantity' => $stockItem->reorder_quantity,
                'details' => "Rifornimento manuale di {$stockItem->product_type}",
            ]);

            $totalCost = $stockItem->reorder_quantity * $stockItem->product->purchase_price;
            SupplierPayment::create([
                'supplier_id' => $supplier->id,
                'product_id' => $stockItem->id,
                'amount' => $totalCost,
                'payment_date' => now(),
            ]);

            Message::create([
                'sender_id' => 2,
                'recipient_id' => $stockItem->manager->id,
                'subject' => 'Rifornimento Completato',
                'body' => "Il rifornimento di {$stockItem->product_type} è stato completato.",
                'is_read' => false,
                'is_archived' => false,
                'is_notification' => true,
                'created_at' => now(),
            ]);

            // Log dell'attività di rifornimento manuale
            CLAIR::logActivity('I', 'initiateManualRestock', 'Rifornimento manuale iniziato', [
                'stock_item_id' => $stockItem->id,
                'reorder_quantity' => $stockItem->reorder_quantity,
                'total_cost' => $totalCost
            ]);

            return redirect()->back()->with('success', 'Rifornimento manuale completato.');
        }

        return redirect()->back()->with('error', 'Impossibile completare il rifornimento. Fornitore non disponibile o stock insufficiente.');
    }
}

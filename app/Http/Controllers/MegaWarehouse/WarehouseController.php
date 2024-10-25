<?php

namespace App\Http\Controllers\MegaWarehouse;

use Carbon\Carbon;
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
        $expiringSoonFresh = Warehouse::where('status', 'expiring_soon')
            ->where('product_type', 'fresh')->get();

        $expiringSoonPackaged = Warehouse::where('status', 'expiring_soon')
            ->where('product_type', 'packaged')->get();

        $pendingDonations = Warehouse::where('status', 'pending_donation')->get();

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
            // Creazione della notifica tramite metodo `message`
            Message::create([
                'sender_id' => 1, // ad esempio, l'ID del sistema o dell'amministratore
                'recipient_id' => $product->manager_id, // ID del manager responsabile
                'subject' => 'Avviso di Scadenza Prodotto',
                'body' => "Il prodotto {$product->product_type} scadrà il {$product->expiry_date}. Verificare le scorte e le vendite.",
                'is_read' => false,
                'is_notification' => true,
            ]);
        }
    }

    public function securityDashboard()
    {
        $operationLogs = WarehouseOperationLog::orderBy('operation_time', 'desc')->paginate(10);
        return view('warehouse.security_dashboard', compact('operationLogs'));
    }

    public function donationDashboard()
    {
        $now = Carbon::now();
        $expiringSoonDate = $now->copy()->addDays(24);
        $donationThresholdDate = $now->copy()->addDays(18);

        // Prodotti che scadono entro 24 giorni
        $expiringProducts = Warehouse::where('expiry_date', '<=', $expiringSoonDate)
            ->where('status', 'expiring_soon')
            ->get();

        // Prodotti pronti per la donazione
        $pendingDonations = Warehouse::where('expiry_date', '<=', $donationThresholdDate)
            ->where('status', 'pending_donation')
            ->get();

        // Prodotti già donati, con paginazione
        $donatedProducts = Donation::with('product')->paginate(10);

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

            return response()->json(['message' => 'Ordine processato e consegna avviata.']);
        } else {
            return response()->json(['error' => 'Prodotto non disponibile.'], 400);
        }
    }

    protected function dispatchDelivery($order)
    {
        DispatchDroneJob::dispatch($order);
    }

    public function viewReports(Request $request)
    {
        $query = WarehouseReport::query();

        if ($request->has('start_date') && $request->start_date) {
            $query->where('report_month', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('report_month', '<=', $request->end_date);
        }

        $reports = $query->orderBy('report_month', 'desc')->paginate(10);
        return view('warehouse.reports', compact('reports'));
    }

    public function supplierPayments()
    {
        $payments = SupplierPayment::orderBy('payment_date', 'desc')->paginate(10);
        return view('warehouse.supplier_payments', compact('payments'));
    }

    public function manageWaste()
    {
        $wastesProduced = [
            'Organico' => 500,
            'Plastica' => 200,
            'Vetro' => 150,
        ];

        foreach ($wastesProduced as $wasteType => $quantity) {
            $waste = WasteType::where('name', $wasteType)->first();

            if ($waste) {
                foreach ($waste->treatments as $treatment) {
                    $output = $quantity * $treatment->output_quantity;

                    if ($treatment->output_resource === 'Compost') {
                        $compostStorage = CompostStorage::firstOrCreate([]);
                        $compostStorage->compostable_material += $output;
                        $compostStorage->save();
                        $compostStorage->processCompost();
                    } else {
                        Resource::updateOrCreate(
                            ['name' => $treatment->output_resource],
                            ['quantity' => DB::raw('quantity + ' . $output)]
                        );
                    }
                }
            }
        }

        return response()->json(['message' => 'Trattamento dei rifiuti completato con successo.']);
    }

    public function lowStock()
    {
        $lowStockItems = Warehouse::whereColumn('quantity', '<', 'min_quantity')->get();
        return view('warehouse.low_stock', compact('lowStockItems'));
    }

    public function checkStock()
    {
        $lowStockItems = Warehouse::where('quantity', '<', 'min_quantity')->get();
        return response()->json($lowStockItems);
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

            return redirect()->back()->with('success', 'Rifornimento manuale completato.');
        }

        return redirect()->back()->with('error', 'Impossibile completare il rifornimento. Fornitore non disponibile o stock insufficiente.');
    }

    public function supplyVendors(Request $request, $vendorId)
    {
        $vendor = Citizen::findOrFail($vendorId);
        $product = Warehouse::where('product_type', $request->product_type)->first();

        if ($product && $product->quantity >= $request->quantity) {
            // Riduci quantità dal magazzino
            $product->quantity -= $request->quantity;
            $product->save();

            // Registra la transazione
            WarehouseTransaction::create([
                'product_id' => $product->id,
                'vendor_id' => $vendor->id,
                'quantity' => $request->quantity,
                'transaction_type' => 'supply',
                'date' => now(),
            ]);

            return response()->json(['message' => 'Rifornimento venditore completato.']);
        }

        return response()->json(['error' => 'Scorte insufficienti.'], 400);
    }
}

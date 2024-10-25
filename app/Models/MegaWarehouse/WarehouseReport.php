<?php

namespace App\Models\MegaWarehouse;

use App\Models\Market\OnlineOrder;
use Illuminate\Support\Facades\DB;
use App\Models\Market\MarketProduct;
use Illuminate\Database\Eloquent\Model;

class WarehouseReport extends Model
{
    protected $fillable = [
        'total_energy_used',
        'recyclable_orders',
        'non_recyclable_orders',
        'waste_generated',
        'report_month',
        'sold_quantity',
        'restocked_quantity',
        'revenue'
    ];

    public static function generateMonthlyReport()
    {
        $reportMonth = now()->startOfMonth();
        $totalEnergy = Warehouse::sum('energy_usage');
        $recyclableOrders = OnlineOrder::where('is_recyclable', true)
            ->whereMonth('created_at', $reportMonth)
            ->count();
        $nonRecyclableOrders = OnlineOrder::where('is_recyclable', false)
            ->whereMonth('created_at', $reportMonth)
            ->count();
        $wasteGenerated = $nonRecyclableOrders * 0.1; // Stima dei rifiuti per ordine non riciclabile

        // Calcolo di sold_quantity, restocked_quantity e revenue
        $soldQuantity = MarketProduct::whereMonth('created_at', $reportMonth)
            ->sum('sold_quantity'); // Assicurati di avere il campo sold_quantity

        $restockedQuantity = MarketProduct::whereMonth('restocked_at', $reportMonth)
            ->sum('restocked_quantity'); // Assicurati di avere il campo restocked_quantity

        $revenue = MarketProduct::whereMonth('created_at', $reportMonth)
            ->sum(DB::raw('sold_quantity * price'));

        // Creazione del report mensile con le nuove metriche
        self::create([
            'total_energy_used' => $totalEnergy,
            'recyclable_orders' => $recyclableOrders,
            'non_recyclable_orders' => $nonRecyclableOrders,
            'waste_generated' => $wasteGenerated,
            'report_month' => $reportMonth,
            'sold_quantity' => $soldQuantity,
            'restocked_quantity' => $restockedQuantity,
            'revenue' => $revenue,
        ]);
    }

    public static function averageEnergyUsage()
    {
        return self::average('total_energy_used');
    }

    public static function recyclableOrderPercentage()
    {
        $totalOrders = self::sum('recyclable_orders') + self::sum('non_recyclable_orders');
        return $totalOrders ? (self::sum('recyclable_orders') / $totalOrders) * 100 : 0;
    }

    public static function totalRevenue()
    {
        return self::sum('revenue');
    }
}

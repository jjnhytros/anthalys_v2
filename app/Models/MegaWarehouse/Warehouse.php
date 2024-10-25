<?php

namespace App\Models\MegaWarehouse;

use App\Events\LowStockDetected;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    public $table = "warehouse";
    protected $fillable = [
        'floor',
        'product_type',
        'quantity',
        'energy_usage',
        'critical_threshold',
        'expiry_date',
        'storage_type',
        'is_donated',
    ];

    // Recupera i prodotti per tipo
    public function getProductsByType($type)
    {
        return $this->where('product_type', $type)->get();
    }

    // Verifica disponibilità dei prodotti
    public function checkAvailability($type, $quantity)
    {
        $product = $this->where('product_type', $type)->first();
        return $product && $product->quantity >= $quantity;
    }

    public function recordEnergyUsage($amount)
    {
        $this->energy_usage += $amount;
        $this->save();
    }

    public function isLowStock()
    {
        return $this->quantity < $this->min_quantity;
    }

    protected static function booted()
    {
        static::updating(function ($warehouse) {
            if ($warehouse->isLowStock()) {
                event(new LowStockDetected($warehouse));
            }
        });
    }
}

<?php

namespace App\Models\MegaWarehouse;

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
}

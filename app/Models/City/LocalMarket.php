<?php

namespace App\Models\City;

use App\Events\LowStockDetected;
use App\Models\Market\MarketProduct;
use Illuminate\Database\Eloquent\Model;

class LocalMarket extends Model
{
    protected $fillable = ['name', 'location', 'city_id'];

    public function products()
    {
        return $this->hasMany(MarketProduct::class);
    }

    public function isLowStock()
    {
        return $this->quantity < $this->min_quantity;
    }

    protected static function booted()
    {
        static::updating(function ($marketProduct) {
            if ($marketProduct->isLowStock()) {
                event(new LowStockDetected($marketProduct));
            }
        });
    }
}

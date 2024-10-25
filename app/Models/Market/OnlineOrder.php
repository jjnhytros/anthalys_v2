<?php

namespace App\Models\Market;

use App\Models\City\Citizen;
use Illuminate\Database\Eloquent\Model;

class OnlineOrder extends Model
{
    protected $fillable = [
        'citizen_id',
        'product_id',
        'quantity',
        'status',
        'packaging_type',
        'is_recyclable',
        'confirmed_at',
        'canceled_at'
    ];

    public function citizen()
    {
        return $this->belongsTo(Citizen::class);
    }

    public function product()
    {
        return $this->belongsTo(MarketProduct::class);
    }

    public function confirm()
    {
        $this->update(['confirmed_at' => now(), 'status' => 'confirmed']);
    }

    public function cancel()
    {
        $this->update(['canceled_at' => now(), 'status' => 'canceled']);
    }

    public function isConfirmed()
    {
        return !is_null($this->confirmed_at);
    }

    public function isCanceled()
    {
        return !is_null($this->canceled_at);
    }

    public function applyDiscountIfVendor()
    {
        if ($this->citizen->ownsStallForProduct($this->product)) {
            $this->product->price *= 0.7; // Applica lo sconto del 30%
        }
    }

    public function markAsRecyclable($type)
    {
        $this->packaging_type = $type;
        $this->is_recyclable = in_array($type, ['biodegradable', 'recyclable']);
        $this->save();
    }
}

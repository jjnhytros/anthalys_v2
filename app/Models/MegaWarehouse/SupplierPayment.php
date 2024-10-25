<?php

namespace App\Models\MegaWarehouse;

use App\Models\Agricolture\Farm;
use App\Models\Market\MarketProduct;
use Illuminate\Database\Eloquent\Model;

class SupplierPayment extends Model
{
    protected $fillable = ['supplier_id', 'product_id', 'amount', 'payment_date'];

    public function supplier()
    {
        return $this->belongsTo(Farm::class);
    }

    public function product()
    {
        return $this->belongsTo(MarketProduct::class);
    }
}

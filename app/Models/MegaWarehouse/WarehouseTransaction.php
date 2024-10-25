<?php

namespace App\Models\MegaWarehouse;

use App\Models\City\Citizen;
use Illuminate\Database\Eloquent\Model;

class WarehouseTransaction extends Model
{
    protected $fillable = ['product_id', 'vendor_id', 'supplier_id', 'quantity', 'transaction_type', 'date'];

    public function product()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Citizen::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Citizen::class);
    }
}

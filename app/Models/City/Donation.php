<?php

namespace App\Models\City;

use App\Models\MegaWarehouse\Warehouse;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = ['product_id', 'quantity', 'donation_date'];

    public function product()
    {
        return $this->belongsTo(Warehouse::class);
    }
}

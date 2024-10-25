<?php

namespace App\Models\Market;

use App\Models\City\Citizen;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = ['citizen_id', 'product_id', 'rating', 'feedback'];

    public function citizen()
    {
        return $this->belongsTo(Citizen::class);
    }

    public function product()
    {
        return $this->belongsTo(MarketProduct::class);
    }
}

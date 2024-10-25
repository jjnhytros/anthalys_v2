<?php

namespace App\Models\City;

use App\Models\Market\MarketProduct;
use Illuminate\Database\Eloquent\Model;

class LocalMarket extends Model
{
    protected $fillable = ['name', 'location', 'city_id'];

    public function products()
    {
        return $this->hasMany(MarketProduct::class);
    }
}

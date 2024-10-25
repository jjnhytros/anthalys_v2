<?php

namespace App\Jobs;

use App\Models\Market\MarketProduct;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateProductPricesJob implements ShouldQueue
{
    public function handle()
    {
        $products = MarketProduct::all();

        foreach ($products as $product) {
            // Aggiorna la domanda e il prezzo del prodotto
            $product->updateDemandAndPrice();
        }
    }
}

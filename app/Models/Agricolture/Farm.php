<?php

namespace App\Models\Agricolture;

use App\Models\City\Citizen;
use App\Models\Market\MarketProduct;
use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    protected $fillable = [
        'name',
        'type',
        'location',
        'soil_health',
        'efficiency',
        'owner_id'
    ];

    public function owner()
    {
        return $this->belongsTo(Citizen::class, 'owner_id');
    }

    public function crops()
    {
        return $this->hasMany(Crop::class);
    }

    public function animals()
    {
        return $this->hasMany(Animal::class);
    }

    public function aquaculture()
    {
        return $this->hasMany(Aquaculture::class);
    }

    public function applyEventImpact($event)
    {
        // Ridurre l'efficienza della fattoria in base alla gravità dell'evento
        $this->efficiency -= $event->severity * $event->impact; // Impatto proporzionale alla gravità e impatto dell'evento
        $this->save();
    }

    // Relazione con i prodotti gestiti dalla fattoria
    public function products()
    {
        return $this->hasMany(MarketProduct::class, 'supplier_id');
    }


    // Verifica se il fornitore ha stock sufficiente per il prodotto specificato
    public function hasProductStock($productId, $quantity)
    {
        // Verifica le scorte nei prodotti coltivati
        $product = $this->products()->where('id', $productId)->first();
        if ($product && $product->quantity >= $quantity) {
            return true;
        }

        // Verifica le scorte nei prodotti di allevamento
        $animalProduct = $this->animals()->where('id', $productId)->first();
        if ($animalProduct && $animalProduct->quantity >= $quantity) {
            return true;
        }

        // Verifica le scorte nei prodotti di acquacoltura
        $aquacultureProduct = $this->aquaculture()->where('id', $productId)->first();
        if ($aquacultureProduct && $aquacultureProduct->quantity >= $quantity) {
            return true;
        }

        return false;
    }

    // Deduce lo stock del prodotto specificato
    public function deductProductStock($productId, $quantity)
    {
        // Deduzione delle scorte nei prodotti coltivati
        $product = $this->products()->where('id', $productId)->first();
        if ($product) {
            $product->decrement('quantity', $quantity);
            return;
        }

        // Deduzione delle scorte nei prodotti di allevamento
        $animalProduct = $this->animals()->where('id', $productId)->first();
        if ($animalProduct) {
            $animalProduct->decrement('quantity', $quantity);
            return;
        }

        // Deduzione delle scorte nei prodotti di acquacoltura
        $aquacultureProduct = $this->aquaculture()->where('id', $productId)->first();
        if ($aquacultureProduct) {
            $aquacultureProduct->decrement('quantity', $quantity);
            return;
        }
    }
}

<?php

namespace App\Models\Market;

use App\Models\Agricolture\Farm;
use App\Models\City\LocalMarket;
use Illuminate\Database\Eloquent\Model;
use App\Models\MegaWarehouse\SupplierPayment;
use App\Models\MegaWarehouse\WarehouseOperationLog;

class MarketProduct extends Model
{
    protected $fillable = [
        'name',
        'quantity',
        'price',
        'purchase_price',
        'market_id',
        'farm_id',
        'stall_id',
        'supplier_id',
        'demand',
        'min_stock_level',
        'reorder_amount'
    ];

    public function market()
    {
        return $this->belongsTo(LocalMarket::class);
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function stall()
    {
        return $this->belongsTo(Stall::class);
    }

    public function updateDemandAndPrice()
    {
        // Aumenta la domanda se la quantità è bassa
        if ($this->quantity < 12) {
            $this->demand += 1;
        }

        // Riduci la domanda se la quantità è abbondante
        if ($this->quantity > 60) {
            $this->demand = max(0, $this->demand - 1);
        }

        // Aggiorna il prezzo in base alla domanda
        // Se la domanda è alta, aumenta il prezzo, altrimenti riducilo
        $demandFactor = 1 + ($this->demand * 0.06); // Incremento del 6% per ogni punto di domanda
        $this->price = $this->purchase_price * $demandFactor;

        // Salva i cambiamenti
        $this->save();
    }

    public function needsRestocking()
    {
        return $this->quantity < $this->min_stock_level;
    }

    public function restock()
    {
        // Verifica che il fornitore sia associato e abbia abbastanza quantità disponibile del prodotto
        if ($this->supplier && $this->supplier->hasProductStock($this->id, $this->reorder_amount)) {

            // Deduzione della quantità in base al tipo di prodotto (coltivazione, allevamento, acquacoltura)
            $this->supplier->deductProductStock($this->id, $this->reorder_amount);

            // Calcolo del pagamento (50% del prezzo finale)
            $payment = ($this->price * $this->reorder_amount) * 0.5;

            // Effettua il pagamento al proprietario della fattoria, se presente
            if ($this->supplier->owner) {
                $this->supplier->owner->increment('cash', $payment);

                // Crea un record di pagamento
                SupplierPayment::create([
                    'supplier_id' => $this->supplier->id,
                    'product_id' => $this->id,
                    'amount' => $payment,
                    'payment_date' => now(),
                ]);
            }

            // Aumenta la quantità disponibile nel magazzino
            $this->quantity += $this->reorder_amount;
            $this->save();

            // Log dell'operazione di rifornimento
            WarehouseOperationLog::create([
                'operation_type' => 'Rifornimento',
                'product_id' => $this->id,
                'quantity' => $this->reorder_amount,
                'details' => "Rifornimento automatico di {$this->reorder_amount} unità per il prodotto {$this->name}.",
            ]);
        } else {
            // Log del fallimento per mancanza di scorte o disassociazione del fornitore
            WarehouseOperationLog::create([
                'operation_type' => 'Fallimento Rifornimento',
                'product_id' => $this->id,
                'quantity' => 0,
                'details' => "Il rifornimento per il prodotto {$this->name} è fallito: scorte insufficienti o fornitore non associato.",
            ]);
        }
    }
}

<?php

namespace App\Models\Agricolture;

use Illuminate\Database\Eloquent\Model;

class CompostStorage extends Model
{
    public $table = 'compost_storage';
    protected $fillable = ['compostable_material', 'available_compost'];

    // Metodo per trasformare il materiale compostabile in compost finale
    public function processCompost()
    {
        $conversionRate = 0.5; // Percentuale di conversione del materiale compostabile in compost
        $compostProduced = $this->compostable_material * $conversionRate;

        // Aggiorna i campi
        $this->compostable_material = 0;
        $this->available_compost += $compostProduced;
        $this->save();
    }
}

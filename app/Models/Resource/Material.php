<?php

namespace App\Models\Resource;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'name',
        'composition',
        'durability',
        'density',
    ];

    public function analyze()
    {
        // Analizza la composizione chimica e strutturale
    }

    public function degrade($amount)
    {
        // Riduce la durabilità del materiale in base a un valore specifico
        $this->durability = max(0, $this->durability - $amount);
        $this->save();
    }
}

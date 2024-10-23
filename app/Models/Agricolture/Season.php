<?php

namespace App\Models\Agricolture;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    protected $fillable = ['name', 'start_day', 'end_day', 'impact_factor'];

    // Metodo per ottenere la stagione attuale in base al giorno dell'anno
    public static function getCurrentSeason()
    {
        $dayOfYear = now()->dayOfYear();

        return self::where('start_day', '<=', $dayOfYear)
            ->where('end_day', '>=', $dayOfYear)
            ->first();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CLAIR extends Model
{
    protected $table = 'clair';

    protected $fillable = [
        'type',       // C, L, A, I, R
        'method',     // Metodo eseguito
        'details',    // Dettagli dell’attività
        'data',
    ];

    // Metodo statico per centralizzare la registrazione delle attività
    public static function logActivity($type, $method, $details, array $data = [])
    {
        self::create([
            'type' => $type,
            'method' => $method,
            'details' => $details,
            'data' => json_encode($data),
        ]);
    }
}

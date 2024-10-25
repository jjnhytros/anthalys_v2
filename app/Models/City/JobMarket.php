<?php

namespace App\Models\City;

use Illuminate\Database\Eloquent\Model;

class JobMarket extends Model
{
    public $table = 'job_market';
    protected $fillable = ['occupation_id', 'demand', 'supply', 'average_salary'];

    public function occupation()
    {
        return $this->belongsTo(Occupation::class);
    }

    // Metodo per aggiornare domanda e offerta
    public static function updateMarketData($occupationId)
    {
        $jobMarket = self::firstOrCreate(['occupation_id' => $occupationId]);
        $jobMarket->demand = CitizenCareer::where('occupation_id', $occupationId)->count();
        $jobMarket->supply = Occupation::where('id', $occupationId)->count();
        $jobMarket->average_salary = Occupation::find($occupationId)->salary;
        $jobMarket->save();
    }
}

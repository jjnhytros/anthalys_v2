<?php

namespace App\Http\Controllers\City;

use App\Models\CLAIR;
use App\Models\City\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\City\EconomyService;

class EconomyController extends Controller
{
    protected $economyService;

    public function __construct(EconomyService $economyService)
    {
        $this->economyService = $economyService;
    }

    public function showEconomy($cityId)
    {
        $city = City::findOrFail($cityId);
        $income = $this->economyService->calculateIncome($city);
        $expenses = $this->economyService->calculateExpenditures($city);

        // Log dell'attività per visualizzazione dei dati economici della città
        CLAIR::logActivity(
            'C',
            'showEconomy',
            'Visualizzazione dei dati economici della città',
            [
                'city_id' => $city->id,
                'income' => $income,
                'expenses' => $expenses,
            ]
        );

        return view('economy.show', compact('income', 'expenses'));
    }
}

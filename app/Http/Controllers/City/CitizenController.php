<?php

namespace App\Http\Controllers\City;

use App\Models\City\Citizen;
use Illuminate\Http\Request;
use App\Models\City\District;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Recycling\RecyclingActivity;
use App\Models\Recycling\RecyclingProgress;
use App\Models\Recycling\DistrictRecyclingGoal;

class CitizenController extends Controller
{
    public function recycle(Request $request)
    {
        $request->validate([
            'resource_type' => 'required|string',
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $user = Auth::user();
        $citizen = $user->citizen;

        if (!$citizen) {
            return back()->with('error', 'Cittadino non trovato.');
        }

        // Calcolo del bonus dinamico in base alla risorsa e alla quantità
        $bonus = $this->calculateBonus($request->resource_type, $request->quantity);

        // Registra l'attività di riciclo
        RecyclingActivity::create([
            'citizen_id' => $citizen->id,
            'resource_type' => $request->resource_type,
            'quantity' => $request->quantity,
            'recycled_at' => now(),
            'bonus' => $bonus,
        ]);

        // Aggiungi il bonus al cittadino
        $citizen->cash += $bonus;
        $citizen->save();

        return back()->with('success', 'Riciclo registrato con successo e bonus di ' . $bonus . '€ ricevuto!');
    }

    public function showRecyclingActivities()
    {
        $user = Auth::user(); // Otteniamo l'utente autenticato
        $citizen = $user->citizen; // Recupera il cittadino associato

        if (!$citizen) {
            return back()->with('error', 'Cittadino non trovato.');
        }

        // Recupera tutte le attività di riciclo per il cittadino
        $recyclingActivities = $citizen->recyclingActivities()->orderBy('recycled_at', 'desc')->get();

        return view('citizen.recycling_activities', compact('recyclingActivities'));
    }

    public function updateDistrictRecyclingProgress(Citizen $citizen, $resourceType, $quantity)
    {
        // Ottieni il distretto del cittadino
        $district = $citizen->residentialBuilding->district;

        // Trova l'obiettivo di riciclo per il distretto
        $recyclingGoal = DistrictRecyclingGoal::where('district_id', $district->id)
            ->where('resource_type', $resourceType)
            ->first();

        // Aggiorna la quantità riciclata attuale
        if ($recyclingGoal) {
            $recyclingGoal->current_quantity += $quantity;
            $recyclingGoal->save();

            // Verifica se l'obiettivo è stato raggiunto
            if ($recyclingGoal->current_quantity >= $recyclingGoal->target_quantity) {
                // Premio collettivo per il distretto (ad esempio, bonus o riduzione tasse)
                $this->rewardDistrict($district);
            }
        }
    }
    public function showRecyclingProgress(Citizen $citizen)
    {
        // Recupera il progresso del riciclo per il cittadino
        $recyclingProgress = RecyclingProgress::where('citizen_id', $citizen->id)->with('wasteType')->get();

        return view('citizens.recycling_progress', compact('citizen', 'recyclingProgress'));
    }

    /**
     * Simula le tasse per tutti i cittadini
     */
    public function simulateTaxes()
    {
        $citizens = Citizen::all();

        foreach ($citizens as $citizen) {
            $citizen->calculateTaxes(); // Calcola e aggiorna le tasse
        }

        return response()->json(['message' => 'Tasse calcolate con successo per tutti i cittadini']);
    }

    protected function calculateBonus($resourceType, $quantity)
    {
        $baseBonusRate = 0.1; // 10% del valore come base
        $bonusMultiplier = 1;

        // Differenti bonus per diversi tipi di risorse
        switch ($resourceType) {
            case 'Plastica':
                $bonusMultiplier = 1.5;
                break;
            case 'Carta':
                $bonusMultiplier = 0.8;
                break;
            case 'Vetro':
                $bonusMultiplier = 1.2;
                break;
            case 'Alluminio':
                $bonusMultiplier = 1.8;
                break;
            default:
                $bonusMultiplier = 1;
                break;
        }

        // Aggiunta di bonus per quantità elevate
        if ($quantity > 100) {
            $bonusMultiplier += 0.2; // Aumenta del 20% per grandi quantità
        } elseif ($quantity > 500) {
            $bonusMultiplier += 0.5; // Aumenta del 50% per quantità molto grandi
        }

        return $quantity * $baseBonusRate * $bonusMultiplier;
    }

    protected function rewardDistrict(District $district)
    {
        // Esempio: ridurre le tasse per tutti i cittadini del distretto
        $citizens = $district->citizens;

        foreach ($citizens as $citizen) {
            $citizen->tax_rate -= 0.01; // Riduci le tasse dell'1% per esempio
            $citizen->save();
        }

        // Inviare una notifica o un premio collettivo
        // Logica per aggiornare infrastrutture, fornire benefici, ecc.
    }
}

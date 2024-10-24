<?php

namespace App\Http\Controllers\Recycling;

use App\Models\City\Citizen;
use App\Http\Controllers\Controller;

class BonusController extends Controller
{
    public function rewardRecycling(Citizen $citizen, $amountRecycled)
    {
        $pointsEarned = $amountRecycled / 10; // 1 punto bonus per ogni 10 unità riciclate
        $citizen->bonus_points += $pointsEarned;
        $citizen->save();

        return response()->json([
            'message' => 'Punti bonus assegnati per il riciclo!',
            'citizen' => $citizen
        ]);
    }

    public function rewardCityImprovement(Citizen $citizen, $activityType)
    {
        $pointsEarned = 0;

        switch ($activityType) {
            case 'piantare_alberi':
                $pointsEarned = 50;
                break;
            case 'evento_pubblico':
                $pointsEarned = 100;
                break;
            default:
                $pointsEarned = 10; // Bonus generico
        }

        $citizen->bonus_points += $pointsEarned;
        $citizen->save();

        return response()->json([
            'message' => 'Punti bonus assegnati per miglioramento della città!',
            'citizen' => $citizen
        ]);
    }
    public function showBonuses(Citizen $citizen)
    {
        // Calcola i bonus in base ai punti di riciclo
        $bonuses = $citizen->calculateBonus();

        return view('citizens.bonus', compact('citizen', 'bonuses'));
    }

    public function claimVoucher(Citizen $citizen)
    {
        // Recupera i bonus e segna il buono come riscattato
        $bonuses = $citizen->calculateBonus();

        if ($bonuses['voucher'] > 0) {
            // Reset dei punti bonus riscattati
            $citizen->recycling_points = max(0, $citizen->recycling_points - 500); // Rimuove 500 punti per ogni buono riscatto
            $citizen->save();

            return redirect()->back()->with('success', 'Hai riscattato un buono di ' . $bonuses['voucher'] . ' AA!');
        }

        return redirect()->back()->with('error', 'Non hai abbastanza punti per riscattare un buono.');
    }
    public function rewardSustainableActivities($citizen)
    {
        $bonus = 100; // Bonus standard per attività sostenibili
        $citizen->bonus_points += $bonus;
        $citizen->save();

        return response()->json(['message' => 'Bonus assegnato con successo!']);
    }
}

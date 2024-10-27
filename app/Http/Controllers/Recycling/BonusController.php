<?php

namespace App\Http\Controllers\Recycling;

use App\Models\CLAIR;
use App\Models\City\Citizen;
use App\Http\Controllers\Controller;

class BonusController extends Controller
{
    public function rewardRecycling(Citizen $citizen, $amountRecycled)
    {
        $pointsEarned = $amountRecycled / 10; // 1 punto bonus per ogni 10 unità riciclate
        $citizen->bonus_points += $pointsEarned;
        $citizen->save();

        // Log dell'attività di riciclo
        CLAIR::logActivity('R', 'rewardRecycling', 'Assegnazione punti bonus per riciclo', [
            'citizen_id' => $citizen->id,
            'amount_recycled' => $amountRecycled,
            'points_earned' => $pointsEarned,
        ]);

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

        // Log dell'attività di miglioramento della città
        CLAIR::logActivity('I', 'rewardCityImprovement', 'Assegnazione punti bonus per miglioramento della città', [
            'citizen_id' => $citizen->id,
            'activity_type' => $activityType,
            'points_earned' => $pointsEarned,
        ]);

        return response()->json([
            'message' => 'Punti bonus assegnati per miglioramento della città!',
            'citizen' => $citizen
        ]);
    }

    public function showBonuses(Citizen $citizen)
    {
        $bonuses = $citizen->calculateBonus();

        // Log dell'attività di visualizzazione dei bonus
        CLAIR::logActivity('C', 'showBonuses', 'Visualizzazione bonus per il cittadino', [
            'citizen_id' => $citizen->id,
            'bonus_details' => $bonuses,
        ]);

        return view('citizens.bonus', compact('citizen', 'bonuses'));
    }

    public function claimVoucher(Citizen $citizen)
    {
        $bonuses = $citizen->calculateBonus();

        if ($bonuses['voucher'] > 0) {
            $citizen->recycling_points = max(0, $citizen->recycling_points - 500);
            $citizen->save();

            // Log dell'attività di riscatto del voucher
            CLAIR::logActivity('R', 'claimVoucher', 'Riscatto voucher bonus', [
                'citizen_id' => $citizen->id,
                'voucher_amount' => $bonuses['voucher'],
            ]);

            return redirect()->back()->with('success', 'Hai riscattato un buono di ' . $bonuses['voucher'] . ' AA!');
        }

        return redirect()->back()->with('error', 'Non hai abbastanza punti per riscattare un buono.');
    }

    public function rewardSustainableActivities(Citizen $citizen)
    {
        $bonus = 100;
        $citizen->bonus_points += $bonus;
        $citizen->save();

        // Log dell'attività di bonus per attività sostenibili
        CLAIR::logActivity('A', 'rewardSustainableActivities', 'Assegnazione punti bonus per attività sostenibili', [
            'citizen_id' => $citizen->id,
            'points_earned' => $bonus,
        ]);

        return response()->json(['message' => 'Bonus assegnato con successo!']);
    }
}

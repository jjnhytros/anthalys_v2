<?php

namespace App\Http\Controllers\City;

use App\Models\CLAIR;
use App\Models\City\Reward;
use Illuminate\Http\Request;
use App\Models\City\LoyaltyPoint;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    public function index()
    {
        $rewards = Reward::all();
        $loyaltyPoints = LoyaltyPoint::where('citizen_id', Auth::user()->citizen->id)->first();

        // Registra l'attività di visualizzazione dei premi disponibili
        CLAIR::logActivity(
            'C',
            'index',
            'Visualizzazione dei premi disponibili',
            ['reward_count' => $rewards->count(), 'citizen_id' => Auth::user()->citizen->id]
        );

        return view('citizens.rewards.index', compact('rewards', 'loyaltyPoints'));
    }

    public function redeem(Reward $reward)
    {
        $loyalty = LoyaltyPoint::where('citizen_id', Auth::user()->citizen->id)->first();

        // Verifica se il cittadino ha abbastanza punti
        if ($loyalty && $loyalty->points >= $reward->points_required) {
            // Sottrai i punti necessari
            $loyalty->points -= $reward->points_required;
            $loyalty->save();

            // Registra l'attività di riscatto del premio
            CLAIR::logActivity(
                'R',
                'redeem',
                'Riscatto di un premio',
                [
                    'citizen_id' => Auth::user()->citizen->id,
                    'reward_id' => $reward->id,
                    'points_spent' => $reward->points_required
                ]
            );

            return redirect()->back()->with('success', 'Premio riscattato con successo!');
        } else {
            // Registra l'attività di tentato riscatto senza punti sufficienti
            CLAIR::logActivity(
                'R',
                'redeem',
                'Tentativo di riscatto fallito per punti insufficienti',
                [
                    'citizen_id' => Auth::user()->citizen->id,
                    'reward_id' => $reward->id,
                    'points_required' => $reward->points_required,
                    'current_points' => $loyalty ? $loyalty->points : 0
                ]
            );

            return redirect()->back()->with('error', 'Non hai abbastanza punti per riscattare questo premio.');
        }
    }
}

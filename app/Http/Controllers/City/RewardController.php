<?php

namespace App\Http\Controllers\City;

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

            return redirect()->back()->with('success', 'Premio riscattato con successo!');
        } else {
            return redirect()->back()->with('error', 'Non hai abbastanza punti per riscattare questo premio.');
        }
    }
}

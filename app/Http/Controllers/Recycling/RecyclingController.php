<?php

namespace App\Http\Controllers\Recycling;

use App\Models\CLAIR;
use App\Models\City\Citizen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Recycling\WasteType;
use App\Http\Controllers\Controller;
use App\Models\Recycling\RecyclingAward;
use App\Models\Recycling\RecyclingProgress;

class RecyclingController extends Controller
{
    public function addPoints(Request $request, Citizen $citizen)
    {
        $wasteType = WasteType::find($request->waste_type_id);
        $quantity = $request->quantity;

        // Sistema di punti in base al tipo di rifiuto
        $pointsPerKg = [
            'Organico' => 1,
            'Carta e Cartone' => 1,
            'Plastica' => 2,
            'Vetro' => 1,
            'Metalli' => 3,
            'Rifiuti Elettronici' => 5,
            'Rifiuti Speciali' => 3,
        ];

        $points = $pointsPerKg[$wasteType->name] * $quantity;

        // Aggiungi i punti al cittadino
        $citizen->addRecyclingPoints($points);

        // Salva il progresso nel riciclo
        RecyclingProgress::create([
            'citizen_id' => $citizen->id,
            'waste_type_id' => $wasteType->id,
            'quantity' => $quantity,
        ]);

        // Log dell'attività di assegnazione punti
        CLAIR::logActivity('C', 'addPoints', 'Punti di riciclo assegnati al cittadino', [
            'citizen_id' => $citizen->id,
            'waste_type' => $wasteType->name,
            'quantity' => $quantity,
            'points_earned' => $points,
        ]);

        return response()->json([
            'message' => 'Punti di riciclo assegnati e progresso salvato!',
            'citizen' => $citizen,
            'points_earned' => $points
        ]);
    }

    public function assignAnnualAwards()
    {
        $currentYear = now()->year;

        // Recupera i cittadini che hanno ridotto al massimo i loro rifiuti
        $topRecyclers = DB::table('citizens')
            ->join('recycling_progress', 'citizens.id', '=', 'recycling_progress.citizen_id')
            ->select('citizens.id', DB::raw('SUM(recycling_progress.points) as total_points'))
            ->groupBy('citizens.id')
            ->orderBy('total_points', 'desc')
            ->take(10)
            ->get();

        foreach ($topRecyclers as $recycler) {
            RecyclingAward::create([
                'citizen_id' => $recycler->id,
                'award_type' => 'Cittadino Sostenibile dell\'Anno',
                'year' => $currentYear,
            ]);
        }

        // Log dell'attività di assegnazione dei premi annuali
        CLAIR::logActivity('R', 'assignAnnualAwards', 'Premi annuali assegnati ai cittadini più virtuosi', [
            'year' => $currentYear,
            'top_recyclers' => $topRecyclers->pluck('id'),
        ]);

        return redirect()->route('recycling.awards')->with('success', 'Premi annuali assegnati con successo!');
    }

    public function viewAwards()
    {
        $awards = RecyclingAward::with('citizen')->orderBy('year', 'desc')->get();

        // Log dell'attività di visualizzazione dei premi
        CLAIR::logActivity('I', 'viewAwards', 'Visualizzazione dei premi di riciclo');

        return view('recycling.awards', compact('awards'));
    }

    public function processRecycling(Citizen $citizen, Request $request)
    {
        $city = $citizen->city;

        // Aggiorna i dati di risparmio delle risorse
        $city->energy_saved += $request->input('energy_saved');
        $city->water_saved += $request->input('water_saved');
        $city->materials_saved += $request->input('materials_saved');

        $city->save();

        // Log dell'attività di processazione del riciclo
        CLAIR::logActivity('A', 'processRecycling', 'Aggiornamento dati di risparmio risorse per il riciclo', [
            'citizen_id' => $citizen->id,
            'energy_saved' => $request->input('energy_saved'),
            'water_saved' => $request->input('water_saved'),
            'materials_saved' => $request->input('materials_saved'),
        ]);

        return redirect()->route('recycling.progress')->with('success', 'Riciclo processato con successo!');
    }
}

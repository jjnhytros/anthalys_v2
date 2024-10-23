<?php

namespace App\Http\Controllers\Recycling;

use App\Models\City\Citizen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Recycling\WasteType;
use App\Http\Controllers\Controller;
use App\Models\Recycling\RecyclingAward;
use App\Models\Recycling\RecyclingProgress;

class RecyclingController extends Controller
{
    // Funzione per aggiornare i punti di riciclo in base al tipo e alla quantità
    public function addPoints(Request $request, Citizen $citizen)
    {
        $wasteType = WasteType::find($request->waste_type_id); // Tipo di rifiuto
        $quantity = $request->quantity; // Quantità di rifiuto riciclato

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

        $points = $pointsPerKg[$wasteType->name] * $quantity; // Calcola i punti

        // Aggiungi i punti al cittadino
        $citizen->addRecyclingPoints($points);

        // Salva il progresso nel riciclo
        RecyclingProgress::create([
            'citizen_id' => $citizen->id,
            'waste_type_id' => $wasteType->id,
            'quantity' => $quantity,
        ]);

        return response()->json([
            'message' => 'Punti di riciclo assegnati e progresso salvato!',
            'citizen' => $citizen,
            'points_earned' => $points
        ]);
    }

    public function assignAnnualAwards()
    {
        // Otteniamo l'anno corrente
        $currentYear = now()->year;

        // Recuperiamo i cittadini che hanno ridotto al massimo i loro rifiuti
        $topRecyclers = DB::table('citizens')
            ->join('recycling_progress', 'citizens.id', '=', 'recycling_progress.citizen_id')
            ->select('citizens.id', DB::raw('SUM(recycling_progress.points) as total_points'))
            ->groupBy('citizens.id')
            ->orderBy('total_points', 'desc')
            ->take(10) // I primi 10 cittadini più virtuosi
            ->get();

        // Assegniamo un premio ai primi 10 cittadini
        foreach ($topRecyclers as $recycler) {
            RecyclingAward::create([
                'citizen_id' => $recycler->id,
                'award_type' => 'Cittadino Sostenibile dell\'Anno',
                'year' => $currentYear,
            ]);
        }

        return redirect()->route('recycling.awards')->with('success', 'Premi annuali assegnati con successo!');
    }

    public function viewAwards()
    {
        $awards = RecyclingAward::with('citizen')->orderBy('year', 'desc')->get();
        return view('recycling.awards', compact('awards'));
    }
    public function processRecycling(Citizen $citizen, Request $request)
    {
        $city = $citizen->city;

        // Aggiorna i dati di risparmio delle risorse basati sul riciclo effettuato
        $city->energy_saved += $request->input('energy_saved');
        $city->water_saved += $request->input('water_saved');
        $city->materials_saved += $request->input('materials_saved');

        $city->save();

        return redirect()->route('recycling.progress')->with('success', 'Riciclo processato con successo!');
    }
}

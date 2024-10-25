<?php

namespace App\Http\Controllers\City;

use App\Models\City\Citizen;
use Illuminate\Http\Request;
use App\Models\City\Occupation;
use App\Http\Controllers\Controller;

class EmploymentCenterController extends Controller
{
    // Visualizza tutte le occupazioni disponibili
    public function index()
    {
        $occupations = Occupation::all();
        return view('employment.index', compact('occupations'));
    }

    // Visualizza i dettagli di un'occupazione specifica
    public function show($id)
    {
        $occupation = Occupation::findOrFail($id);
        return view('employment.show', compact('occupation'));
    }

    // Gestisce la candidatura di un cittadino a una posizione specifica
    public function apply(Request $request, $occupationId)
    {
        $citizen = Citizen::findOrFail($request->citizen_id);
        $occupation = Occupation::findOrFail($occupationId);

        // Verifica se il cittadino soddisfa i requisiti per l'occupazione
        if ($citizen->isEligibleForOccupation($occupation)) {
            // Crea la carriera se soddisfa i requisiti
            $citizen->career()->create([
                'occupation_id' => $occupation->id,
                'level' => 1, // Livello iniziale
                'experience' => 0,
                'reputation' => 0,
            ]);

            return redirect()->route('employment.index')->with('success', 'Candidatura inviata con successo!');
        } else {
            return redirect()->route('employment.index')->with('error', 'Non soddisfi i requisiti per questa occupazione.');
        }
    }

    public function search(Request $request)
    {
        $query = Occupation::query();

        if ($request->has('skill_id')) {
            $query->whereHas('skills', function ($query) use ($request) {
                $query->where('skill_id', $request->skill_id);
            });
        }

        if ($request->has('reputation_level')) {
            $query->where('required_reputation', '<=', $request->reputation_level);
        }

        if ($request->has('min_salary')) {
            $query->where('salary', '>=', $request->min_salary);
        }

        $occupations = $query->get();

        return view('employment.search', compact('occupations'));
    }
}

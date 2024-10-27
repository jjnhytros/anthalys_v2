<?php

namespace App\Http\Controllers\City;

use App\Models\CLAIR;
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

        // Log dell'attività per visualizzare l'elenco delle occupazioni
        CLAIR::logActivity(
            'C',
            'index',
            'Visualizzazione di tutte le occupazioni disponibili',
            ['total_occupations' => $occupations->count()]
        );

        return view('employment.index', compact('occupations'));
    }

    // Visualizza i dettagli di un'occupazione specifica
    public function show($id)
    {
        $occupation = Occupation::findOrFail($id);

        // Log dell'attività per visualizzare i dettagli di un'occupazione specifica
        CLAIR::logActivity(
            'C',
            'show',
            'Visualizzazione dei dettagli dell\'occupazione',
            ['occupation_id' => $id, 'occupation_name' => $occupation->name]
        );

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
            $career = $citizen->career()->create([
                'occupation_id' => $occupation->id,
                'level' => 1, // Livello iniziale
                'experience' => 0,
                'reputation' => 0,
            ]);

            // Log dell'attività per la candidatura
            CLAIR::logActivity(
                'I',
                'apply',
                'Candidatura per una posizione',
                ['citizen_id' => $citizen->id, 'occupation_id' => $occupation->id]
            );

            return redirect()->route('employment.index')->with('success', 'Candidatura inviata con successo!');
        } else {
            // Log per il fallimento della candidatura
            CLAIR::logActivity(
                'I',
                'apply',
                'Tentativo di candidatura non riuscito per mancanza di requisiti',
                ['citizen_id' => $citizen->id, 'occupation_id' => $occupation->id]
            );

            return redirect()->route('employment.index')->with('error', 'Non soddisfi i requisiti per questa occupazione.');
        }
    }

    // Ricerca di occupazioni
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

        // Log dell'attività di ricerca
        CLAIR::logActivity(
            'C',
            'search',
            'Ricerca di occupazioni in base a criteri specifici',
            [
                'criteria' => $request->only(['skill_id', 'reputation_level', 'min_salary']),
                'results' => $occupations->count()
            ]
        );

        return view('employment.search', compact('occupations'));
    }
}

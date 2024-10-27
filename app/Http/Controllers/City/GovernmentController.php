<?php

namespace App\Http\Controllers\City;

use App\Models\CLAIR;
use Illuminate\Http\Request;
use App\Models\City\WorkPolicy;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class GovernmentController extends Controller
{
    public function showPolicies()
    {
        $policy = WorkPolicy::first();

        // Log dell'attività per visualizzare le politiche di lavoro
        CLAIR::logActivity(
            'C',
            'showPolicies',
            'Visualizzazione delle politiche di lavoro',
            ['policy_id' => $policy->id]
        );

        return view('government.policies', compact('policy'));
    }

    public function updatePolicies(Request $request)
    {
        $policy = WorkPolicy::first();
        $policy->update($request->all());

        // Log dell'attività per l'aggiornamento delle politiche di lavoro
        CLAIR::logActivity(
            'I',
            'updatePolicies',
            'Aggiornamento delle politiche di lavoro',
            ['policy_id' => $policy->id, 'updated_data' => $request->all()]
        );

        return redirect()->route('government.policies')->with('status', 'Politiche aggiornate con successo!');
    }

    public function showReports()
    {
        $files = Storage::files('reports');
        $reports = collect($files)->map(function ($file) {
            $year = (int) str_replace(['reports/government_report_', '.json'], '', $file);
            return (object) ['year' => $year];
        })->sortByDesc('year');

        // Log dell'attività per la visualizzazione dell'elenco dei report
        CLAIR::logActivity(
            'C',
            'showReports',
            'Visualizzazione dei report governativi',
            ['total_reports' => $reports->count()]
        );

        return view('government.reports', compact('reports'));
    }

    public function viewReport($year)
    {
        $path = storage_path("app/reports/government_report_{$year}.json");
        $report = json_decode(file_get_contents($path), true);

        // Log dell'attività per la visualizzazione di un report specifico
        CLAIR::logActivity(
            'C',
            'viewReport',
            'Visualizzazione del report governativo per l\'anno specificato',
            ['year' => $year]
        );

        return view('government.view_report', compact('report', 'year'));
    }
}

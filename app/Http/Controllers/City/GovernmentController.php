<?php

namespace App\Http\Controllers\City;

use Illuminate\Http\Request;
use App\Models\City\WorkPolicy;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class GovernmentController extends Controller
{
    public function showPolicies()
    {
        $policy = WorkPolicy::first();
        return view('government.policies', compact('policy'));
    }

    public function updatePolicies(Request $request)
    {
        $policy = WorkPolicy::first();
        $policy->update($request->all());

        return redirect()->route('government.policies')->with('status', 'Politiche aggiornate con successo!');
    }
    public function showReports()
    {
        $files = Storage::files('reports');
        $reports = collect($files)->map(function ($file) {
            $year = (int) str_replace(['reports/government_report_', '.json'], '', $file);
            return (object) ['year' => $year];
        })->sortByDesc('year');

        return view('government.reports', compact('reports'));
    }

    public function viewReport($year)
    {
        $path = storage_path("app/reports/government_report_{$year}.json");
        $report = json_decode(file_get_contents($path), true);

        return view('government.view_report', compact('report', 'year'));
    }
}
<?php

namespace App\Http\Controllers\City;

use App\Models\CLAIR;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Agricolture\ProductionReport;

class ProductionReportController extends Controller
{
    public function index()
    {
        $reports = ProductionReport::all();

        // Registra l'attività di visualizzazione dell'elenco dei report di produzione
        CLAIR::logActivity(
            'C',
            'index',
            'Visualizzazione dell\'elenco dei report di produzione',
            ['report_count' => $reports->count()]
        );

        return view('cities.reports.index', compact('reports'));
    }

    public function show(ProductionReport $report)
    {
        // Registra l'attività di visualizzazione di un report di produzione specifico
        CLAIR::logActivity(
            'C',
            'show',
            'Visualizzazione del report di produzione',
            ['report_id' => $report->id]
        );

        return view('cities.reports.show', compact('report'));
    }
}

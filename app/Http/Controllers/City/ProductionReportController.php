<?php

namespace App\Http\Controllers\City;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Agricolture\ProductionReport;

class ProductionReportController extends Controller
{
    public function index()
    {
        $reports = ProductionReport::all();
        return view('cities.reports.index', compact('reports'));
    }

    public function show(ProductionReport $report)
    {
        return view('cities.reports.show', compact('report'));
    }
}

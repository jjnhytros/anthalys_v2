<?php

namespace App\Http\Controllers\City;

use App\Models\CLAIR;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\City\MaterialAnalysisService;

class MaterialAnalysisController extends Controller
{
    protected $materialAnalysisService;

    public function __construct(MaterialAnalysisService $materialAnalysisService)
    {
        $this->materialAnalysisService = $materialAnalysisService;
    }

    public function showMaterialAnalysis($materialId)
    {
        $material = Material::findOrFail($materialId);

        // Analisi della composizione del materiale
        $composition = $this->materialAnalysisService->analyzeMaterialComposition($material);

        // Tracciamento del degrado del materiale
        $degradation = $this->materialAnalysisService->trackDegradation($material);

        // Log dell'analisi e del tracciamento
        CLAIR::logActivity(
            'C',
            'showMaterialAnalysis',
            'Analisi della composizione e tracciamento del degrado del materiale',
            [
                'material_id' => $material->id,
                'composition' => $composition,
                'degradation' => $degradation,
            ]
        );

        return view('material.show', compact('composition', 'degradation'));
    }
}

<?php

namespace App\Http\Controllers\Recycling;

use App\Models\Resource\Resource;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Recycling\WasteTreatment;
use App\Models\Recycling\RecyclingProgress;

class WasteTreatmentController extends Controller
{
    // Funzione per trattare i rifiuti e generare risorse
    public function treatWaste()
    {
        // Recupera i progressi di riciclo
        $recyclingProgress = RecyclingProgress::all();

        // Inizializza un array per raccogliere le risorse generate
        $resourcesGenerated = [];

        foreach ($recyclingProgress as $progress) {
            // Recupera il tipo di trattamento per il rifiuto
            $treatment = WasteTreatment::where('waste_type', $progress->wasteType->name)->first();

            // Se esiste un trattamento per questo rifiuto, calcola la risorsa generata
            if ($treatment) {
                $generated = $progress->quantity * $treatment->output_quantity;

                // Aumenta la produzione ottimizzata della risorsa
                $resource = Resource::where('name', $treatment->output_resource)->first();
                if ($resource) {
                    $resource->optimized_production += $generated;
                    $resource->save();
                }

                // Aggiungi al report di risorse generate
                $resourcesGenerated[] = [
                    'resource' => $treatment->output_resource,
                    'quantity' => $generated,
                ];
            }
        }

        // Ritorna le risorse generate dal trattamento dei rifiuti
        return view('waste.treatment.result', compact('resourcesGenerated'));
    }
    public function monitorResources()
    {
        // Recupera le risorse generate raggruppate per tipo
        $resourcesGenerated = DB::table('recycling_progress')
            ->join('waste_treatments', 'recycling_progress.waste_type_id', '=', 'waste_treatments.id')
            ->select(
                'waste_treatments.output_resource as resource',
                DB::raw('SUM(recycling_progress.quantity * waste_treatments.output_quantity) as total_generated')
            )
            ->groupBy('resource')
            ->get();

        return view('waste.treatment.monitor', compact('resourcesGenerated'));
    }
}

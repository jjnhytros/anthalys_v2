<?php

namespace App\Http\Controllers\City;

use App\Models\CLAIR;
use Illuminate\Http\Request;
use App\Models\City\District;
use App\Http\Controllers\Controller;

class BuildingController extends Controller
{
    public function index(District $district)
    {
        // Recupera gli edifici del distretto
        $buildings = $district->buildings;

        // Registra l'attività di visualizzazione degli edifici
        CLAIR::logActivity('C', 'index', 'Visualizzazione degli edifici del distretto', [
            'district_id' => $district->id,
            'building_count' => $buildings->count()
        ]);

        return view('buildings.index', compact('district', 'buildings'));
    }

    public function create(District $district)
    {
        // Verifica se il distretto può supportare nuovi edifici
        $canSupportNewBuilding = CLAIR::resilience()->canSupportNewBuilding($district);

        // Registra l'attività di creazione dell'edificio
        CLAIR::logActivity('R', 'create', 'Verifica della capacità del distretto di supportare nuovi edifici', [
            'district_id' => $district->id,
            'can_support' => $canSupportNewBuilding
        ]);

        return view('buildings.create', compact('district', 'canSupportNewBuilding'));
    }

    public function store(Request $request, District $district)
    {
        // Aggiunge il nuovo edificio al distretto
        $building = $district->buildings()->create($request->all());

        // Adatta le risorse del distretto per accomodare il nuovo edificio
        CLAIR::adaptation()->adjustResourcesForNewBuilding($district, $building);

        // Predice l'impatto futuro del nuovo edificio
        $predictedImpact = CLAIR::learning()->predictBuildingImpact($building);

        // Invia una notifica al governo riguardo al nuovo edificio
        CLAIR::integration()->sendNotification(
            2, // ID del governo
            $district->manager_id,
            'Nuovo Edificio Aggiunto',
            "Un nuovo edificio è stato aggiunto al distretto {$district->name}. Impatto previsto: " . json_encode($predictedImpact)
        );

        // Registra l'attività di creazione dell'edificio e adattamento risorse
        CLAIR::logActivity('A', 'store', 'Creazione di un nuovo edificio e adattamento delle risorse', [
            'district_id' => $district->id,
            'building_id' => $building->id,
            'predicted_impact' => $predictedImpact
        ]);

        return redirect()->route('districts.buildings.index', $district);
    }
}

<?php

namespace App\Http\Controllers\City;

use App\Models\CLAIR;
use App\Models\City\Citizen;
use Illuminate\Http\Request;
use App\Models\City\District;
use App\Models\City\Occupation;
use App\Models\City\CitizenCareer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Recycling\RecyclingActivity;
use App\Models\Recycling\RecyclingProgress;
use App\Models\Recycling\DistrictRecyclingGoal;

class CitizenController extends Controller
{
    public function recycle(Request $request)
    {
        $request->validate([
            'resource_type' => 'required|string',
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $user = Auth::user();
        $citizen = $user->citizen;

        if (!$citizen) {
            return back()->with('error', 'Cittadino non trovato.');
        }

        $bonus = $this->calculateBonus($request->resource_type, $request->quantity);

        // Registra l'attività di riciclo
        $activity = RecyclingActivity::create([
            'citizen_id' => $citizen->id,
            'resource_type' => $request->resource_type,
            'quantity' => $request->quantity,
            'recycled_at' => now(),
            'bonus' => $bonus,
        ]);

        // Aggiungi il bonus al cittadino
        $citizen->cash += $bonus;
        $citizen->save();

        CLAIR::logActivity('C', 'recycle', 'Riciclo effettuato dal cittadino', [
            'citizen_id' => $citizen->id,
            'resource_type' => $request->resource_type,
            'quantity' => $request->quantity,
            'bonus' => $bonus
        ]);

        return back()->with('success', 'Riciclo registrato con successo e bonus di ' . $bonus . '€ ricevuto!');
    }

    public function showRecyclingActivities()
    {
        $user = Auth::user();
        $citizen = $user->citizen;

        if (!$citizen) {
            return back()->with('error', 'Cittadino non trovato.');
        }

        $recyclingActivities = $citizen->recyclingActivities()->orderBy('recycled_at', 'desc')->get();

        CLAIR::logActivity('I', 'showRecyclingActivities', 'Visualizzazione attività di riciclo del cittadino', [
            'citizen_id' => $citizen->id,
            'activity_count' => $recyclingActivities->count()
        ]);

        return view('citizen.recycling_activities', compact('recyclingActivities'));
    }

    public function updateDistrictRecyclingProgress(Citizen $citizen, $resourceType, $quantity)
    {
        $district = $citizen->residentialBuilding->district;

        $recyclingGoal = DistrictRecyclingGoal::where('district_id', $district->id)
            ->where('resource_type', $resourceType)
            ->first();

        if ($recyclingGoal) {
            $recyclingGoal->current_quantity += $quantity;
            $recyclingGoal->save();

            CLAIR::logActivity('A', 'updateDistrictRecyclingProgress', 'Aggiornamento del progresso di riciclo nel distretto', [
                'district_id' => $district->id,
                'resource_type' => $resourceType,
                'quantity_added' => $quantity,
                'current_quantity' => $recyclingGoal->current_quantity
            ]);

            if ($recyclingGoal->current_quantity >= $recyclingGoal->target_quantity) {
                $this->rewardDistrict($district);
            }
        }
    }

    public function showRecyclingProgress(Citizen $citizen)
    {
        $recyclingProgress = RecyclingProgress::where('citizen_id', $citizen->id)->with('wasteType')->get();

        CLAIR::logActivity('I', 'showRecyclingProgress', 'Visualizzazione del progresso di riciclo per il cittadino', [
            'citizen_id' => $citizen->id,
            'progress_count' => $recyclingProgress->count()
        ]);

        return view('citizens.recycling_progress', compact('citizen', 'recyclingProgress'));
    }

    public function simulateTaxes()
    {
        $citizens = Citizen::all();

        foreach ($citizens as $citizen) {
            $citizen->calculateTaxes();
        }

        CLAIR::logActivity('R', 'simulateTaxes', 'Simulazione delle tasse per tutti i cittadini', [
            'citizen_count' => $citizens->count()
        ]);

        return response()->json(['message' => 'Tasse calcolate con successo per tutti i cittadini']);
    }

    protected function calculateBonus($resourceType, $quantity)
    {
        $baseBonusRate = 0.1;
        $bonusMultiplier = match ($resourceType) {
            'Plastica' => 1.5,
            'Carta' => 0.8,
            'Vetro' => 1.2,
            'Alluminio' => 1.8,
            default => 1,
        };

        if ($quantity > 500) {
            $bonusMultiplier += 0.5;
        } elseif ($quantity > 100) {
            $bonusMultiplier += 0.2;
        }

        return $quantity * $baseBonusRate * $bonusMultiplier;
    }

    protected function rewardDistrict(District $district)
    {
        foreach ($district->citizens as $citizen) {
            $citizen->tax_rate -= 0.01;
            $citizen->save();
        }

        CLAIR::logActivity('R', 'rewardDistrict', 'Premio collettivo per il distretto', [
            'district_id' => $district->id,
            'tax_reduction' => 0.01
        ]);
    }

    public function assignOccupation(Request $request, Citizen $citizen)
    {
        $request->validate(['occupation_id' => 'required|exists:occupations,id']);

        $occupation = Occupation::findOrFail($request->occupation_id);
        $career = CitizenCareer::create([
            'citizen_id' => $citizen->id,
            'occupation_id' => $occupation->id,
            'level' => 1,
            'reputation' => 0,
            'experience' => 0,
        ]);

        // Registra l'attività di assegnazione dell'occupazione tramite CLAIR
        CLAIR::logActivity('I', 'assignOccupation', 'Assegnazione di una nuova occupazione al cittadino', [
            'citizen_id' => $citizen->id,
            'occupation_id' => $occupation->id,
            'career_id' => $career->id,
            'level' => 1,
            'reputation' => 0,
            'experience' => 0,
        ]);

        return response()->json(['message' => 'Occupazione assegnata', 'career' => $career]);
    }

    public function gainExperience(CitizenCareer $career, $experiencePoints)
    {
        // Incrementa l'esperienza e salva
        $career->experience += $experiencePoints;
        $career->save();

        // Aggiorna il livello della carriera
        $career->promote();

        // Registra l'acquisizione di esperienza tramite CLAIR
        CLAIR::logActivity('L', 'gainExperience', 'Aggiunta di esperienza per il cittadino nella carriera', [
            'career_id' => $career->id,
            'added_experience' => $experiencePoints,
            'total_experience' => $career->experience,
            'level' => $career->level
        ]);

        return response()->json(['message' => 'Esperienza guadagnata', 'career' => $career]);
    }

    public function imposeFinesForNonCompliance()
    {
        $citizens = Citizen::where('taxes_due', '>', 1000)->get(); // Cittadini con tasse arretrate superiori a 1000 AA

        foreach ($citizens as $citizen) {
            $fine = $citizen->taxes_due * 0.1; // Sanzione del 10% delle tasse arretrate
            $citizen->cash -= $fine;
            $citizen->save();

            // Notifica il cittadino della sanzione ricevuta
            $this->notifyCitizenOfFine($citizen, $fine);

            // Registra l'attività di imposizione della sanzione
            CLAIR::logActivity(
                'T', // Tipo di attività per Tax
                'imposeFinesForNonCompliance',
                'Imposizione di sanzioni per mancato pagamento delle tasse',
                ['citizen_id' => $citizen->id, 'fine_amount' => $fine]
            );
        }

        return response()->json(['message' => 'Sanzioni imposte ai cittadini non conformi.']);
    }

    private function notifyCitizenOfFine($citizen, $fine)
    {
        // Notifica il cittadino della sanzione
        $citizen->notify(new CitizenFineNotification(
            'Hai ricevuto una sanzione di ' . number_format($fine, 2) . ' AA per il mancato pagamento delle tasse.'
        ));
    }
}

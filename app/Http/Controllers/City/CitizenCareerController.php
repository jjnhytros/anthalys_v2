<?php

namespace App\Http\Controllers\City;

use App\Models\City\Citizen;
use Illuminate\Http\Request;
use App\Models\City\Occupation;
use App\Models\City\CitizenCareer;
use App\Http\Controllers\Controller;

class CitizenCareerController extends Controller
{
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

        return response()->json(['message' => 'Occupazione assegnata', 'career' => $career]);
    }

    public function gainExperience(CitizenCareer $career, $experiencePoints)
    {
        $career->experience += $experiencePoints;
        $career->save();

        $career->promote();

        return response()->json(['message' => 'Esperienza guadagnata', 'career' => $career]);
    }
}

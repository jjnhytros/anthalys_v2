<?php

namespace App\Models\City;

use Illuminate\Database\Eloquent\Model;

class CitizenSkill extends Model
{
    protected $fillable = ['citizen_id', 'skill_id', 'level', 'experience'];

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function citizen()
    {
        return $this->belongsTo(Citizen::class);
    }

    public function addExperience($points)
    {
        $experienceMultiplier = 1 + ($this->level * 0.01);
        $adjustedPoints = $points * $experienceMultiplier;
        $this->experience += $adjustedPoints;

        $nextLevel = ExperienceLevel::where('level', $this->level + 1)->first();

        if ($nextLevel && $this->experience >= $nextLevel->experience_required) {
            if ($this->level < ExperienceLevel::max('level')) {
                $this->level++;
                $this->experience -= $nextLevel->experience_required;
            } else {
                $this->experience = $nextLevel->experience_required; // Blocca l'esperienza al massimo livello
            }
        }

        $this->save();
    }
}

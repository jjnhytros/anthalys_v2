<?php

namespace App\Models\City;

use Illuminate\Database\Eloquent\Model;

class CitizenCareer extends Model
{
    protected $fillable = ['citizen_id', 'occupation_id', 'level', 'reputation', 'experience'];

    public function citizen()
    {
        return $this->belongsTo(Citizen::class);
    }

    public function occupation()
    {
        return $this->belongsTo(Occupation::class);
    }

    public function promote()
    {
        $nextLevel = ExperienceLevel::where('level', $this->level + 1)->first();

        if ($nextLevel && $this->experience >= $nextLevel->experience_required) {
            $this->level++;
            $this->experience -= $nextLevel->experience_required;

            // Assicurati che il livello massimo non venga superato
            if ($this->level >= ExperienceLevel::max('level')) {
                $this->experience = 0; // Resetta l'esperienza una volta raggiunto il massimo
            }

            $this->save();
        }
    }
}

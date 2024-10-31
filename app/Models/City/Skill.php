<?php
// app/Models/City/Skill.php
namespace App\Models\City;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = ['name', 'description'];

    public function citizens()
    {
        return $this->belongsToMany(Citizen::class, 'citizen_skills')
            ->withPivot('level', 'experience')
            ->withTimestamps();
    }

    public function addExperience($amount)
    {
        $this->pivot->experience += $amount;
        $levelThreshold = $this->getExperienceRequiredForNextLevel();

        if ($this->pivot->experience >= $levelThreshold) {
            $this->pivot->experience -= $levelThreshold;
            $this->pivot->level++;
        }
        $this->pivot->save();
    }
}

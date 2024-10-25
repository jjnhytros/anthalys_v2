<?php

namespace App\Models\City;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

class Citizen extends Model
{
    protected $fillable = [
        'name',
        'is_employed',
        'income',
        'cash',
        'salary',
        'taxes_due',
        'hours_worked',
        'is_working',
        'bonus_points',
        'recycling_points',
        'district_id',
        'residential_building_id',
        'work_building_id',
        'city_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relazione con l'edificio residenziale (dove il cittadino vive)
    public function residentialBuilding()
    {
        return $this->belongsTo(Building::class, 'residential_building_id');
    }

    // Relazione con l'edificio di lavoro (commerciale o industriale)
    public function workBuilding()
    {
        return $this->belongsTo(Building::class, 'work_building_id');
    }

    // Relazione con la città
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function buildings()
    {
        return $this->hasMany(Building::class, 'citizen_id'); // Assumendo che un cittadino possa avere più edifici
    }

    public function scopeSupplier($query)
    {
        return $query->where('role', 'supplier');
    }

    public function scopeVendor($query)
    {
        return $query->where('role', 'vendor');
    }


    /**
     * Calcola le tasse basate sulla politica fiscale attiva
     */
    public function calculateTaxes()
    {
        $tax_brackets = json_decode(WorkPolicy::first()->tax_brackets, true);
        $income = $this->income;
        $tax_due = 0;

        foreach ($tax_brackets as $bracket) {
            [$min_income, $max_income, $rate] = $bracket;
            if ($income > $min_income && ($max_income === null || $income <= $max_income)) {
                $tax_due += ($income - $min_income) * $rate;
                break;
            }
        }

        // Versare al governo
        $government = User::where('name', 'government')->first();
        $government->cash += $tax_due;
        $government->save();

        $this->cash -= $tax_due;
        $this->save();

        return $tax_due;
    }


    public function calculateSubsidies()
    {
        $subsidy_threshold = WorkPolicy::first()->healthcare_for_low_income;
        $subsidy_amount = 2000;

        if ($this->income < $subsidy_threshold) {
            $government = User::where('name', 'government')->first();
            if ($government->cash >= $subsidy_amount) {
                $government->cash -= $subsidy_amount;
                $government->save();

                $this->cash += $subsidy_amount;
                $this->save();

                // Copertura sanitaria per reddito basso
                $this->receiveHealthcareSubsidy();

                return $subsidy_amount;
            }
        }

        return 0;
    }

    public function hasLowIncome()
    {
        $policy = WorkPolicy::first();
        return $this->income < $policy->healthcare_for_low_income;
    }

    public function receiveHealthcareSubsidy()
    {
        $policy = WorkPolicy::first();

        // Verifica se il cittadino ha diritto alla copertura sanitaria per reddito basso
        if ($this->income < $policy->healthcare_for_low_income) {
            $subsidy_amount = 1000; // Sussidio per la copertura sanitaria

            // Recuperiamo il governo e verifichiamo se ha abbastanza fondi
            $government = User::where('name', 'government')->first();

            if ($government->cash >= $subsidy_amount) {
                // Preleva il sussidio dal governo
                $government->cash -= $subsidy_amount;
                $government->save();
            } else {
                // Se non ci sono fondi, si può restituire un messaggio di errore o avviare una logica alternativa
                Log::warning('Fondi insufficienti per copertura sanitaria.');
                return false;
            }

            // Aggiungiamo il sussidio ai fondi del cittadino
            $this->cash += $subsidy_amount;
            $this->save();

            return true;
        }

        return false;
    }
    public function calculatePension()
    {
        // Condizioni per la pensione di base e massima
        $basePensionYears = 20;  // 20 anni per ottenere il 50% dello stipendio medio
        $maxPensionYears = 35;   // 35 anni per ottenere il 100% dello stipendio medio
        $basePensionRate = 0.50; // Percentuale della pensione di base
        $maxPensionRate = 1.00;  // Percentuale della pensione massima

        // Calcola la percentuale della pensione in base agli anni di servizio
        if ($this->years_of_service >= $maxPensionYears) {
            $pensionRate = $maxPensionRate;
        } elseif ($this->years_of_service >= $basePensionYears) {
            // Calcola la pensione in modo proporzionale tra 20 e 35 anni
            $pensionRate = $basePensionRate + (($this->years_of_service - $basePensionYears) / ($maxPensionYears - $basePensionYears)) * ($maxPensionRate - $basePensionRate);
        } else {
            $pensionRate = 0;
        }

        // Calcola la pensione come percentuale del reddito medio
        $averageIncome = $this->getAverageIncome(); // Metodo per ottenere il reddito medio del cittadino
        $pensionAmount = $pensionRate * $averageIncome;

        return $pensionAmount;
    }

    public function getAverageIncome()
    {
        // Metodo per ottenere il reddito medio del cittadino
        // Puoi implementare una logica per calcolare la media su base annua o mese
        return $this->income; // Qui consideriamo l'attuale reddito come media per semplicità
    }

    public function sendNotification($subject, $message, $options = [])
    {
        $this->messages()->create([
            'subject' => $subject,
            'message' => $message,
            'url' => $options['url'] ?? null,
            'is_notification' => true, // Per segnalarla come notifica
            'is_message' => false,
            'is_email' => false,
            'status' => 'unread',
        ]);
    }
}

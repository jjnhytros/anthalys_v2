<?php

namespace App\Models\City;

use Illuminate\Database\Eloquent\Model;

class WorkPolicy extends Model
{
    protected $fillable = [
        'work_hours_per_day',
        'work_days_per_week',
        'work_months_per_year',
        'vacation_days',
        'sick_leave_days',
        'maternity_leave_days',
        'tax_brackets', // JSON per conservare le aliquote fiscali
        'pension_conditions',
        'healthcare_for_low_income',
        'benefits',
    ];
}

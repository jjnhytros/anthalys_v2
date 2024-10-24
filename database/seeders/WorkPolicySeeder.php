<?php

namespace Database\Seeders;

use App\Models\City\WorkPolicy;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WorkPolicySeeder extends Seeder
{
    public function run()
    {
        WorkPolicy::create([
            'work_hours_per_day' => 9,
            'work_days_per_week' => 5,
            'work_months_per_year' => 15,
            'vacation_days' => 24,
            'sick_leave_days' => 10,
            'maternity_leave_days' => 30,
            'tax_brackets' => json_encode([
                [0, 3000, 0],
                [3001, 6000, 0.015],
                [6001, 12000, 0.03],
                [12001, 15000, 0.045],
                [15001, 18000, 0.06],
                [18001, 21000, 0.075],
                [21001, 24000, 0.09],
                [24001, 27000, 0.105],
                [27001, 30000, 0.12],
                [30001, 33000, 0.135],
                [33001, 36000, 0.15],
                [36001, 39000, 0.165],
                [39001, 42000, 0.18],
                [42001, 45000, 0.195],
                [45001, 48000, 0.21],
                [48001, 51000, 0.225],
                [51001, 144000, 0.24],
                [144001, null, 0.26],
            ]),
            'pension_conditions' => json_encode([
                'base_pension_years' => 20,
                'base_hours_required' => 54000,
                'base_percentage' => 0.50,
                'max_pension_years' => 35,
                'max_hours_required' => 143100,
                'max_percentage' => 1.00,
                'pension_tax_rate' => 0.015
            ]),
            'healthcare_for_low_income' => 6000,
            'benefits' => json_encode([
                'maternity_leave' => 'Congedo retribuito per nuovi genitori',
                'job_security' => 'Norme rigorose di sicurezza e salute sul lavoro'
            ])
        ]);
    }
}

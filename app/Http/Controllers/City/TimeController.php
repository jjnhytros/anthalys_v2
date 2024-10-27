<?php

namespace App\Http\Controllers\City;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\City\TimeService;
use App\Http\Controllers\Controller;

class TimeController extends Controller
{
    protected $timeService;

    public function __construct(TimeService $timeService)
    {
        $this->timeService = $timeService;
    }

    public function calculateElapsedTime()
    {
        // Data di partenza nel calendario terrestre
        $startDate = Carbon::create(2000, 2, 27);
        $now = Carbon::now();

        // Calcola la differenza in secondi totali
        $secondsElapsed = $startDate->diffInSeconds($now);

        // Converte in base al sistema di Anthalys
        $secondsInAnthalMinute = 1; // 1 secondo reale è 1 minuto anthaliano
        $secondsInAnthalHour = $secondsInAnthalMinute * 60;
        $secondsInAnthalDay = $secondsInAnthalHour * 28;
        $secondsInAnthalMonth = $secondsInAnthalDay * 24;
        $secondsInAnthalYear = $secondsInAnthalMonth * 18;

        // Conversione
        $years = intdiv($secondsElapsed, $secondsInAnthalYear);
        $secondsElapsed %= $secondsInAnthalYear;

        $months = intdiv($secondsElapsed, $secondsInAnthalMonth);
        $secondsElapsed %= $secondsInAnthalMonth;

        $days = intdiv($secondsElapsed, $secondsInAnthalDay);
        $secondsElapsed %= $secondsInAnthalDay;

        $hours = intdiv($secondsElapsed, $secondsInAnthalHour);
        $secondsElapsed %= $secondsInAnthalHour;

        $minutes = intdiv($secondsElapsed, $secondsInAnthalMinute);
        $seconds = $secondsElapsed % $secondsInAnthalMinute;

        // Passa i dati alla vista
        return view('time', compact('years', 'months', 'days', 'hours', 'minutes', 'seconds'));
        // return response()->json([
        //     'year' => $years,
        //     'month' => $months,
        //     'day' => $days,
        //     'hour' => $hours,
        //     'minute' => $minutes,
        //     'second' => $seconds,
        // ]);
    }
}

<?php

namespace App\Services\City;

class TimeService
{
    private $currentSecond = 0;
    private $currentMinute = 0;
    private $currentHour = 0;
    private $currentDay = 1;
    private $currentMonth = 1;
    private $currentYear = 1;

    // Tassi di precessione in gradi per anno Anthaliano
    private $precessionRateOmega = 0.0001;
    private $precessionRateOmegaRadians;
    private $precessionRatePerihelion = 0.0001;
    private $precessionRatePerihelionRadians;

    // Valori iniziali
    private $omega = 130.832565; // Longitudine iniziale del nodo ascendente in gradi
    private $perihelion = 133.2447175; // Argomento del perielio in gradi

    public function __construct()
    {
        // Converti i tassi di precessione in radianti
        $this->precessionRateOmegaRadians = deg2rad($this->precessionRateOmega);
        $this->precessionRatePerihelionRadians = deg2rad($this->precessionRatePerihelion);
    }


    public function tick()
    {
        $this->currentMinute++;

        if ($this->currentSecond >= 60) {
            $this->currentSecond = 0;
            $this->currentMinute++;
        }

        if ($this->currentMinute >= 60) {
            $this->currentMinute = 0;
            $this->currentHour++;
        }

        if ($this->currentHour >= 28) {
            $this->currentHour = 0;
            $this->currentDay++;
        }

        if ($this->currentDay > 24) {
            $this->currentDay = 1;
            $this->currentMonth++;
        }

        if ($this->currentMonth > 18) {
            $this->currentMonth = 1;
            $this->currentYear++;
        }
    }

    public function getCurrentTime()
    {
        return [
            'year' => $this->currentYear,
            'month' => $this->currentMonth,
            'day' => $this->currentDay,
            'hour' => $this->currentHour,
            'minute' => $this->currentMinute,
            'second' => $this->currentSecond,
        ];
    }
}

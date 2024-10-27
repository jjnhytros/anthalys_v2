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

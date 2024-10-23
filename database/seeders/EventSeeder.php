<?php

namespace Database\Seeders;

use App\Models\City\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run()
    {
        $events = [
            ['type' => 'Tempesta', 'description' => 'Una tempesta ha ridotto la produzione di energia del 30%.', 'impact' => 0.70],
            ['type' => 'Epidemia', 'description' => 'Un\'epidemia ha aumentato il consumo di cibo e acqua del 20%.', 'impact' => 1.20],
            ['type' => 'Carestia', 'description' => 'Una carestia ha ridotto la produzione di cibo del 25%.', 'impact' => 0.75],
            ['type' => 'Boom Demografico', 'description' => 'La città sta attraversando una crescita demografica rapida, aumentando la popolazione del 10%.', 'impact' => 1.10],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}

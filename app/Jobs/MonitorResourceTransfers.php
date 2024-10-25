<?php

namespace App\Jobs;

use App\Models\City\District;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;


class MonitorResourceTransfers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $districts = District::all();

        foreach ($districts as $district) {
            app('App\Http\Controllers\ResourceController')->checkResourceNeeds($district);
            app('App\Http\Controllers\ResourceController')->monitorResourceConsumption($district);
        }
    }
}

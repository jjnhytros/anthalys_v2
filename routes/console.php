<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('simulate:daily')->daily();
Schedule::command('simulate:growth')->weekly();
Schedule::command('simulate:taxescollect')->daily();
Schedule::command('simulate:increase-resource-production')->daily();
Schedule::command('simulate:auto-transfer-resources')->hourly();
Schedule::command('simulate:agricultural-production')->hourly();

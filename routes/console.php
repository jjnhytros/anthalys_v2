<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('simulate:daily')->daily();
Schedule::command('simulate:growth')->weekly();

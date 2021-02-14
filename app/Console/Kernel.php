<?php

namespace App\Console;

use App\Console\Commands\GenerateStaticApiConfigCommand;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        GenerateStaticApiConfigCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        //
    }
}

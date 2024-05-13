<?php

namespace App\Console;

use App\Http\Controllers\ManagementController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Route;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected $commands = [
        Commands\PushEmail::class
    ];
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('pushemail:cron');
        // $schedule->command('inspire')->everySecond();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

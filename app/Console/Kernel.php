<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Run database backups daily at midnight Indian Standard Time (IST)
        $schedule->command('backup:run --only-db')
            ->timezone('Asia/Kolkata')
            ->dailyAt('00:00')
            ->before(function () {
                Log::info('Scheduled backup:run --only-db started.');
            })
            ->after(function () {
                Log::info('Scheduled backup:run --only-db finished.');
            });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

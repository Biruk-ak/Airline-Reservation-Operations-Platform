<?php
namespace App\Console;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('flights:sync-status')->everyMinute();
        $schedule->command('crew:check-duty-limits')->hourly();
        $schedule->command('maintenance:evaluate-due')->hourly();
        $schedule->command('baggage:reconcile')->everyFiveMinutes();
        $schedule->command('weather:refresh-airports')->everyTenMinutes();
        $schedule->command('analytics:rollup-daily')->dailyAt('01:30');
        $schedule->command('loyalty:expire-points')->daily();
        $schedule->command('pricing:recalculate-fares')->everyFifteenMinutes();
        $schedule->command('gates:auto-assign')->everyFiveMinutes();
        $schedule->command('cargo:capacity-audit')->hourly();
        $schedule->command('reports:generate-operational')->dailyAt('02:00');
        $schedule->command('tracking:poll-positions')->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}

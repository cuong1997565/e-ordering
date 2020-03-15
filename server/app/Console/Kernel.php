<?php

namespace App\Console;

use App\Console\Commands\CheckCreditLimitDashBoard;
use App\Console\Commands\CheckDashBoardPOandSODate;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [CheckDashBoardPOandSODate::class, CheckCreditLimitDashBoard::class];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            shell_exec('php /var/www/html/artisan queue:retry all');
        })->everyMinute();

        $schedule->command('command:CheckDashBoardPOandSODate')->daily();

        $schedule->command('command:CheckCreditLimitDashBoard')->daily();
    }
}

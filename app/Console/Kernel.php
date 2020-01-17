<?php
/** @noinspection ReturnTypeCanBeDeclaredInspection */
declare(strict_types=1);

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

/**
 * Class Kernel
 *
 * @package App\Console
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\GrabWotE::class,
        Commands\GrabInfo::class,
        Commands\GrabMap::class,
        Commands\GrabStatus::class,
        Commands\GrabTranslations::class,
        Commands\GrabNews::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('grab:wote')->daily();
        $schedule->command('grab:news')->hourly();
        $schedule->command('grab:info')->everyMinute();
        $schedule->command('grab:status')->everyMinute();
        $schedule->command('grab:map')->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}

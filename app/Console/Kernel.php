<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ServeCustom::class, // Ваша кастомная команда
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Здесь можно определить планировщик задач, если это нужно
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        // Регистрирует команды, определенные в других файлах
        $this->load(__DIR__.'/Commands');

        // Регистрирует другие команды, если нужно
        require base_path('routes/console.php');
    }
}
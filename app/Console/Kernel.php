<?php

namespace App\Console;

use App\Twitter\TwitterCollect;
use App\Classes\IGHashTag;
use App\Classes\IGMention;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            (new IGHashTag())->pullMedias();
            (new IGMention())->pullMedias();
        })->cron('* * * * *');

        /*
        $schedule->call(function () {
            (new TwitterCollect())->pullMedias();
        })->hourly();
        */
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

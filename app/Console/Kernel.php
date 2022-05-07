<?php

namespace App\Console;

use App\Classes\FBFeed;
use App\Classes\FBMention;
use App\Classes\FbTerm;
use App\Classes\IGHashTag;
use App\Classes\IGMention;
use App\Classes\Rule;
use App\Twitter\TwitterCollect;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\EmailCron::class,
        Commands\NotificacaoCron::class
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('notificacao:cron')->hourly();

        $schedule->call(function () {
            (new IGHashTag())->pullMedias();
            (new IGMention())->pullMedias();
            (new TwitterCollect())->pullMedias();
            (new FBMention())->pullMedias();
            (new FBFeed())->pullMedias();   
            (new Rule())->runJob();         
            (new FbTerm())->runJob();    
        })->hourly()->between('2:00', '23:00');

    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
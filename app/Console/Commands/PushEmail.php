<?php

namespace App\Console\Commands;

use App\Helpers\AuditeeHelper;
use App\Http\Controllers\ManagementController;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Routing\Route;

class PushEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pushemail:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push Email Auditee For Reminder Audit Progress';

    /**
     * Execute the console command.
     */
    public function handle(Schedule $schedule)
    {
        $schedule->call(function(){
            // $audits = Route::get('/audit-closes-end', [ManagementController::class, 'auditCloses']);
            $audits = app('App\Http\Controllers\ManagementController\auditCloses');
            AuditeeHelper::notifyEmailAuditee($audits);
        });
    }
}

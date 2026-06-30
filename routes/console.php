<?php

//use Illuminate\Foundation\Inspiring;
//use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');*/

Schedule::command('leads:update-lead-state')->dailyAt('16:05')->withoutOverlapping();
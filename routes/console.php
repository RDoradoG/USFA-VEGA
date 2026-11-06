<?php

//use Illuminate\Foundation\Inspiring;
//use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');*/

Schedule::command('leads:update-lead-state')->dailyAt('02:05')->timezone('America/La_Paz')->withoutOverlapping();
Schedule::command('sync:seguimientos')->dailyAt('03:05')->timezone('America/La_Paz')->withoutOverlapping();
//Schedule::command('sync:abandonos')->dailyAt('04:05')->timezone('America/La_Paz')->withoutOverlapping();
//Schedule::command('sync:deserciones')->dailyAt('05:05')->timezone('America/La_Paz')->withoutOverlapping();
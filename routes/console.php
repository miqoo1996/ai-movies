<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('shows:fetch --all')
    ->daily()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/shows-fetch.log'))
    ->then(fn () => Artisan::call('shows:enrich', [], fopen(storage_path('logs/shows-enrich.log'), 'a')));

Schedule::command('shows:download-posters')
    ->daily()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/shows-download-posters.log'));

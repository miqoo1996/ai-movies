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
    ->appendOutputTo(storage_path('logs/shows-fetch.log'));

Schedule::command('shows:enrich --provider=gemini')
    ->dailyAt('03:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/shows-enrich.log'));

Schedule::command('shows:download-posters')
    ->daily()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/shows-download-posters.log'));

Schedule::command('shows:fetch-images')
    ->daily()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/shows:fetch-images.log'));

Schedule::command('episodes:download-thumbs')
    ->daily()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/episodes-download-thumbs.log'));

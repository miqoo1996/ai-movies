<?php

namespace App\Providers;

use App\Models\Genre;
use App\Services\OpenAIService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(OpenAIService::class, fn () => new OpenAIService());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $view->with('navGenres', Genre::orderBy('name')->get());
        });
    }
}

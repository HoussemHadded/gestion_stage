<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\CacheService::class, function ($app) {
            return new \App\Services\CacheService();
        });

        // Phase 4 — EvaluationService (singleton)
        $this->app->singleton(\App\Services\EvaluationService::class, function ($app) {
            return new \App\Services\EvaluationService();
        });

        // Phase 4 — DashboardService (singleton)
        $this->app->singleton(\App\Services\DashboardService::class, function ($app) {
            return new \App\Services\DashboardService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}

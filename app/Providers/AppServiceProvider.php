<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\FinancialYear;
use App\Observers\FinancialYearObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register FinancialYear observer to auto-create increment_ids entry
        FinancialYear::observe(FinancialYearObserver::class);

        if ($this->app->runningInConsole()) {
            // Increase memory limit for artisan commands, such as large database backups
            ini_set('memory_limit', '512M');
        }
    }
}

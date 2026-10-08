<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\BloodRequest;
use App\Observers\BloodRequestObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        BloodRequest::observe(BloodRequestObserver::class);
    }
}
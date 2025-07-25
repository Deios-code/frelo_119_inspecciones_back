<?php

namespace App\Providers;

use App\Interfaces\SuperAdmin\StationsRepositoryInterface;
use App\Repositories\SuperAdmin\StationsRepository;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(StationsRepositoryInterface::class, StationsRepository::class);
    }
}

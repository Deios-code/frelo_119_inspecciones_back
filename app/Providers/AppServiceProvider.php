<?php

namespace App\Providers;

use App\Interfaces\Admin\EstablishmentRepositoryInterface as AdminEstablishmentRepositoryInterface;
use App\Interfaces\Admin\StationsRepositoryInterface as AdminStationsRepositoryInterface;
use App\Interfaces\Admin\InspectorsRepositoryInterface as AdminInspectorsRepositoryInterface;
use App\Repositories\Admin\EstablishmentRepository as AdminEstablishmentRepository;
use App\Repositories\Admin\StationsRepository as AdminStationsRepository;
use App\Repositories\Admin\InspectorsRepository as AdminInspectorsRepository;

use App\Interfaces\SuperAdmin\EstablishmentRepositoryInterface;
use App\Interfaces\SuperAdmin\InspectorsRepositoryInterface;
use App\Interfaces\SuperAdmin\StationsRepositoryInterface;
use App\Repositories\SuperAdmin\EstablishmentRepository;
use App\Repositories\SuperAdmin\StationsRepository;
use App\Repositories\SuperAdmin\InspectorsRepository;

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
        $this->app->bind(EstablishmentRepositoryInterface::class, EstablishmentRepository::class);
        $this->app->bind(AdminStationsRepositoryInterface::class, AdminStationsRepository::class);
        $this->app->bind(AdminEstablishmentRepositoryInterface::class, AdminEstablishmentRepository::class);
        $this->app->bind(InspectorsRepositoryInterface::class, InspectorsRepository::class);
        $this->app->bind(AdminInspectorsRepositoryInterface::class, AdminInspectorsRepository::class);
    }
}

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

use App\Interfaces\Auth\LoginRepositoryInterface;
use App\Interfaces\Auth\RegisterRepositoryInterface;

use App\Interfaces\User\EstablishmentRepositoryInterface as UserEstablishmentRepositoryInterface;
use App\Repositories\User\EstablishmentRepository as UserEstablishmentRepository;

use App\Repositories\Auth\LoginRepository;
use App\Repositories\Auth\RegisterRepository;

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
        $this->app->bind(InspectorsRepositoryInterface::class, InspectorsRepository::class);

        $this->app->bind(AdminStationsRepositoryInterface::class, AdminStationsRepository::class);
        $this->app->bind(AdminEstablishmentRepositoryInterface::class, AdminEstablishmentRepository::class);
        $this->app->bind(AdminInspectorsRepositoryInterface::class, AdminInspectorsRepository::class);

        $this->app->bind(UserEstablishmentRepositoryInterface::class, UserEstablishmentRepository::class);

        $this->app->bind(LoginRepositoryInterface::class, LoginRepository::class);
        $this->app->bind(RegisterRepositoryInterface::class, RegisterRepository::class);
    }
}

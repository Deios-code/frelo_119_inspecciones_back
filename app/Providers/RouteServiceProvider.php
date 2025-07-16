<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(function () {
                    $directory = base_path('routes/api');
                    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

                    foreach ($iterator as $file) {
                        if ($file->isFile() && $file->getExtension() === 'php') {
                            require $file->getPathname();
                        }
                    }
                });

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}

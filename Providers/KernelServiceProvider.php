<?php

namespace Kernel\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Kernel\Commands\KernelCommand;
use Kernel\Generator;
use Kernel\Middleware\Authenticate;
use Kernel\Middleware\LogMiddleware;
use Kernel\Middleware\PermissionMiddleware;

class KernelServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        app()->withFacades();
        app()->withEloquent();
        app()->configure('kernel');
        $this->mergeConfigFrom(__DIR__ . '/../config/kernel.php', 'kernel');
        app()->routeMiddleware([
            'auth' => Authenticate::class,
            'permission' => PermissionMiddleware::class,
            'log' => LogMiddleware::class,
        ]);
        app()->register(AuthServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        $this->commands(KernelCommand::class);
        Generator::generateRoutesData()
            ->map(fn($route) => Route::post($route['uri'], [
                'middleware' => $route['middleware'],
                'uses' => $route['uses']
            ]));
    }
}

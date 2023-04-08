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
        require_once __DIR__ . '/../helper.php';

        app()->routeMiddleware([
            'auth' => Authenticate::class,
            'permission' => PermissionMiddleware::class,
            'log' => LogMiddleware::class,
        ]);

        app()->register(AuthServiceProvider::class);

        app()->configure('kernel');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        $this->mergeConfigFrom(__DIR__ . '/../config/kernel.php', 'kernel');

        Generator::generateRoutesData()
            ->map(fn($route) => Route::post($route['uri'], [
                'middleware' => $route['middleware'],
                'uses' => $route['uses']
            ]));

        $this->commands(KernelCommand::class);
    }
}

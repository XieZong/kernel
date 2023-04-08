<?php

namespace Kernel;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

abstract class BaseRoute
{
    protected string $module;
    protected string $module_name;

    protected static string $name;
    protected static string $prefix;
    protected static string $controller;
    protected static array $middleware = ['auth', 'permission', 'log'];

    private static Collection $routes;

    private static self $instance;

    private static function boot(): void
    {
        self::$instance = new static();
        self::loadModule();
        self::loadRoute();
    }

    private static function loadModule(): void
    {
        method_exists(self::$instance, 'module') && call_user_func([self::$instance, 'module']);
    }

    private static function loadRoute(): void
    {
        $instance = new ReflectionClass(self::$instance);
        self::$routes = collect($instance->getMethods())
            ->filter(function (ReflectionMethod $method) {
                if (!$method->getReturnType()) return false;
                return $method->getReturnType()->getName() === Route::class;
            })
            ->map(fn(ReflectionMethod $method) => call_user_func($method->invoke($method->isStatic() ? null : self::$instance)));
    }

    public final static function generateRouteData(): Collection
    {
        self::boot();
        return self::$routes
            ->map(fn($route) => [
                'uri' => self::getRouteUri($route['path']),
                'uses' => implode('@', [static::$controller, $route['path']]),
                'middleware' => $route['middleware'] ?? static::$middleware
            ]);
    }

    public final static function generatePermissionData(): Collection
    {
        self::boot();
        return static::$routes
            ->filter(fn($route) => in_array('permission', $route['middleware'] ?? static::$middleware))
            ->map(fn($route) => [
                'label' => $route['label'],
                'value' => base64_encode(implode('@', [static::$controller, $route['path']])),
                'name' => self::getName(),
                'module' => self::getModuleName()
            ]);
    }

    public final static function generateApiData(): Collection
    {
        self::boot();
        return static::$routes
            ->map(fn($route) => [
                'label' => $route['label'],
                'value' => self::getRouteUri($route['path']),
                'name' => self::getName(),
                'module' => self::getModuleName(),
                'request' => $route['request'] ?? [],
                'response' => $route['response'] ?? [],
            ]);
    }

    public final static function generateLogData(): Collection
    {
        self::boot();
        return static::$routes
            ->filter(fn($route) => in_array('log', $route['middleware'] ?? static::$middleware))
            ->map(fn($route) => [
                'label' => implode('-', array_filter([self::getModuleName(), self::getName(), $route['label']])),
                'value' => base64_encode(implode('@', [static::$controller, $route['path']])),
            ]);
    }

    private static function getRouteUri($path): string
    {
        $uri = [
            self::getBasePrefix(),
            self::getModule(),
            self::getPrefix(),
            trim($path, '/')
        ];
        return implode('/', array_filter($uri));
    }

    private static function getBasePrefix(): string
    {
        return trim(config('kernel.prefix'), '/');
    }

    private static function getModule(): string
    {
        return trim(self::$instance->module ?? '', '/');
    }

    private static function getPrefix(): string
    {
        if (isset(static::$prefix)) return trim(static::$prefix, '/');
        $prefix = class_basename(static::class);
        $prefix = str_replace('Route', '', $prefix);
        return Str::snake($prefix);
    }

    private static function getName(): string
    {
        if (isset(static::$name)) return static::$name;
        $name = class_basename(static::class);
        return str_replace('Route', '', $name);
    }

    private static function getModuleName(): string
    {
        return self::$instance->module_name ?? '';
    }
}

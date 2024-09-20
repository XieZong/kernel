<?php

namespace Kernel;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Generator
{
    const KERNEL_CACHE_ROUTE = 'kernel-cache-route';
    const KERNEL_CACHE_PERMISSION = 'kernel-cache-permission';
    const KERNEL_CACHE_API = 'kernel-cache-api';
    const KERNEL_CACHE_LOG = 'kernel-cache-log';

    private static function generateData(): Collection
    {
        return collect(config('kernel.path', []))
            ->map(fn($path, $namespace) => self::getPath($path)->map(function ($path) use ($namespace) {
                $namespace = self::getNamespace($path, $namespace);
                $full_path = base_path($path);
                $dir = scandir($full_path);
                $file = array_filter(array_map(fn($item) => str_replace(['.php', '.'], '', $item), $dir));
                return array_map(fn($item) => implode('\\', [$namespace, $item]), $file);
            })->collapse())
            ->collapse()
            ->concat(config('kernel.register', []))
            ->unique();
    }

    private static function getPath(string $path): Collection
    {
        $path = trim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR);
        if (!str_contains($path, '*')) return collect($path);
        $before = Str::before($path, '*');
        $after = Str::after($path, '*');
        $full_path = base_path(rtrim($before, DIRECTORY_SEPARATOR));
        $dir = array_filter(scandir($full_path), fn($value) => !($value == '.' || $value == '..'));
        return collect($dir)->map(fn($item) => "{$before}{$item}{$after}");

    }

    private static function getNamespace(string $path, string $namespace): string
    {
        if (!is_numeric($namespace)) return $namespace;
        $path = str_replace('/', '\\', $path);
        return Str::studly($path);
    }

    public static function generateRoutesData(): Collection
    {
        /**
         * @var BaseRoute $route
         */
        return Cache::get(
            self::KERNEL_CACHE_ROUTE,
            fn() => self::generateData()->map(fn($route) => $route::generateRouteData())->collapse()
        );
    }

    public static function generatePermissionsData(): Collection
    {
        /**
         * @var BaseRoute $route
         */
        return Cache::get(
            self::KERNEL_CACHE_PERMISSION,
            fn() => self::modularization(self::generateData()->map(fn($route) => $route::generatePermissionData()))
        );
    }

    public static function generateApisData(): Collection
    {
        /**
         * @var BaseRoute $route
         */
        return Cache::get(
            self::KERNEL_CACHE_API,
            fn() => self::modularization(self::generateData()->map(fn($route) => $route::generateApiData()))
        );
    }

    public static function generateLogsData(): Collection
    {
        /**
         * @var BaseRoute $route
         */
        return Cache::get(
            self::KERNEL_CACHE_LOG,
            fn() => self::generateData()
                ->sortBy(fn($route) => $route::$sort)
                ->map(fn($route) => $route::generateLogData())
                ->collapse()
        );
    }

    private static function modularization(Collection $data): Collection
    {
        return $data
            ->groupBy(fn($item) => $item['module_sort'] . '#' . $item['module'])
            ->sortKeys(SORT_NATURAL)
            ->map(fn($item, $key) => [
                'label' => Str::after($key, '#'),
                'children' => $item->sortBy('sort')->map(fn($item) => [
                    'label' => $item['label'],
                    'children' => $item['children'],
                ])->values(),
            ])->values();
    }
}

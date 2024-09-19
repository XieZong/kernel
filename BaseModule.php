<?php

namespace Kernel;

use Illuminate\Support\Str;
use ReflectionClass;

trait BaseModule
{
    protected function module(): void
    {
        $reflection = new ReflectionClass($this);
        $this->module = self::getModule($reflection);
        $this->module_name = self::getModuleName($reflection);
        $this->module_sort = self::getModuleSort($reflection);
    }

    private static function getModule($instance): string
    {
        if ($module = $instance->getConstant('MODULE')) return $module;
        $trait = collect($instance->getTraits())->first(fn(ReflectionClass $trait) => in_array(__TRAIT__, array_keys($trait->getTraits())));
        $module = class_basename($trait->name);
        $module = str_replace('Module', '', $module);
        return Str::snake($module);
    }

    private static function getModuleName($instance): string
    {
        if ($name = $instance->getConstant('MODULE_NAME')) return $name;
        $trait = collect($instance->getTraits())->first(fn(ReflectionClass $trait) => in_array(__TRAIT__, array_keys($trait->getTraits())));
        return class_basename($trait->name);
    }

    private static function getModuleSort($instance): int
    {
        if ($sort = $instance->getConstant('MODULE_SORT')) return $sort;
        else return self::$sort;
    }
}

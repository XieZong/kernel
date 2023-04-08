<?php

namespace Kernel;

use Illuminate\Support\Str;
use ReflectionClass;

trait BaseModule
{
    protected function module(): void
    {
        $this->module = self::getModule();
        $this->module_name = self::getModuleName();
    }

    private static function getModule(): string
    {
        $instance = new ReflectionClass(__CLASS__);
        if ($module = $instance->getConstant('MODULE')) return $module;
        $trait = collect($instance->getTraits())->first(fn(ReflectionClass $trait) => in_array(__TRAIT__, array_keys($trait->getTraits())));
        $module = class_basename($trait->name);
        $module = str_replace('Module', '', $module);
        return Str::snake($module);
    }

    private static function getModuleName(): string
    {
        $instance = new ReflectionClass(__CLASS__);
        if ($name = $instance->getConstant('MODULE_NAME')) return $name;
        $trait = collect($instance->getTraits())->first(fn(ReflectionClass $trait) => in_array(__TRAIT__, array_keys($trait->getTraits())));
        return class_basename($trait->name);
    }
}

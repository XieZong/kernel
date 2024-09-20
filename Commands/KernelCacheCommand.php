<?php

namespace Kernel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Kernel\Generator;

class KernelCacheCommand extends Command
{
    protected $signature = 'kernel:cache {--C|clear : 清理缓存}';

    protected $description = '缓存核心数据';

    public function handle(): void
    {
        $clear = $this->option('clear');
        if ($clear) {
            Cache::forget(Generator::KERNEL_CACHE_ROUTE);
            Cache::forget(Generator::KERNEL_CACHE_PERMISSION);
            Cache::forget(Generator::KERNEL_CACHE_API);
            Cache::forget(Generator::KERNEL_CACHE_LOG);
            $this->info('清理成功!');
        } else {
            Cache::set(Generator::KERNEL_CACHE_ROUTE, Generator::generateRoutesData());
            Cache::set(Generator::KERNEL_CACHE_PERMISSION, Generator::generatePermissionsData());
            Cache::set(Generator::KERNEL_CACHE_API, Generator::generateApisData());
            Cache::set(Generator::KERNEL_CACHE_LOG, Generator::generateLogsData());
            $this->info('缓存成功!');
        }
    }
}

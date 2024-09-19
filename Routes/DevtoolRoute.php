<?php

namespace Kernel\Routes;

use Kernel\BaseRoute;
use Kernel\Controllers\DevtoolController;
use Kernel\Route;

class DevtoolRoute extends BaseRoute
{
    public static int $sort = 30;
    protected static string $name = '开发工具';
    protected static string $controller = DevtoolController::class;

    public function permission(): Route
    {
        return new Route(
            path: __FUNCTION__,
            label: '权限列表',
            middleware: array_diff(self::$middleware, ['log'])
        );
    }

    public function api(): Route
    {
        return new Route(
            path: __FUNCTION__,
            label: '接口列表',
            middleware: array_diff(self::$middleware, ['log'])
        );
    }
}

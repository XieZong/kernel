<?php

namespace Kernel\Routes;

use Kernel\BaseRoute;
use Kernel\Controllers\LoginLogController;
use Kernel\Modules\LogModule;
use Kernel\Route;

class LoginLogRoute extends BaseRoute
{
    use LogModule;

    protected static string $prefix = 'login';
    protected static string $name = '登录日志';
    protected static string $controller = LoginLogController::class;

    public function index(): Route
    {
        return new Route(
            path: __FUNCTION__,
            label: '日志列表',
            middleware: array_diff(self::$middleware, ['log'])
        );
    }
}

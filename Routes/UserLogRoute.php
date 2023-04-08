<?php

namespace Kernel\Routes;

use Kernel\BaseRoute;
use Kernel\Controllers\UserLogController;
use Kernel\Modules\LogModule;
use Kernel\Route;

class UserLogRoute extends BaseRoute
{
    use LogModule;

    protected static string $prefix = 'user';
    protected static string $name = '操作日志';
    protected static string $controller = UserLogController::class;

    public function index(): Route
    {
        return new Route(
            path: __FUNCTION__,
            label: '日志列表',
            middleware: array_diff(self::$middleware, ['log'])
        );
    }
}

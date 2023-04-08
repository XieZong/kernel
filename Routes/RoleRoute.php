<?php

namespace Kernel\Routes;

use Kernel\BaseRoute;
use Kernel\Controllers\RoleController;
use Kernel\Modules\SystemModule;
use Kernel\Route;

class RoleRoute extends BaseRoute
{
    use SystemModule;

    protected static string $name = '角色管理';
    protected static string $controller = RoleController::class;

    public function index(): Route
    {
        return new Route(
            path: __FUNCTION__,
            label: '角色列表',
            middleware: array_diff(self::$middleware, ['log'])
        );
    }

    public function store(): Route
    {
        return new Route(
            path: __FUNCTION__,
            label: '角色添加'
        );
    }

    public function update(): Route
    {
        return new Route(
            path: __FUNCTION__,
            label: '角色修改'
        );
    }

    public function destroy(): Route
    {
        return new Route(
            path: __FUNCTION__,
            label: '角色删除'
        );
    }
}

<?php

namespace Kernel\Routes;

use Kernel\BaseRoute;
use Kernel\Controllers\UserController;
use Kernel\Docs\UserDoc;
use Kernel\Modules\SystemModule;
use Kernel\Route;

class UserRoute extends BaseRoute
{
    use SystemModule;

    protected static string $name = '用户管理';
    protected static string $controller = UserController::class;

    public function login(): Route
    {
        return Route::init(
            path: __FUNCTION__,
            label: '用户登录',
            middleware: []
        )->setDoc(UserDoc::login());
    }

    public function info(): Route
    {
        return new Route(
            path: __FUNCTION__,
            label: '用户信息',
            middleware: ['auth']
        );
    }

    public function logout(): Route
    {
        return new Route(
            path: __FUNCTION__,
            label: '用户登出',
            middleware: ['auth']
        );
    }

    public function passwd(): Route
    {
        return new Route(
            path: __FUNCTION__,
            label: '用户改密',
            middleware: ['auth']
        );
    }

    public function index(): Route
    {
        return new Route(
            path: __FUNCTION__,
            label: '用户列表',
            middleware: array_diff(self::$middleware, ['log'])
        );
    }

    public function store(): Route
    {
        return new Route(
            path: __FUNCTION__,
            label: '用户添加'
        );
    }

    public function update(): Route
    {
        return new Route(
            path: __FUNCTION__,
            label: '用户修改'
        );
    }

    public function destroy(): Route
    {
        return new Route(
            path: __FUNCTION__,
            label: '用户删除'
        );
    }
}

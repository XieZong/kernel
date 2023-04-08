<?php

namespace Kernel\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate
{
    protected Auth $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next, $guard = null)
    {
        if ($this->auth->guard($guard)->guest()) {
            return json(false, '用户未登录', 401);
        }

        return $next($request);
    }
}

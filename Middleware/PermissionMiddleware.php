<?php

namespace Kernel\Middleware;

use Closure;
use Illuminate\Support\Arr;

class PermissionMiddleware
{
    public function handle($request, Closure $next)
    {
        if (user('is_admin')) return $next($request);
        $permissions = user('all_permissions');
        $route = $request->route();
        $uses = Arr::get($route[1], 'uses');
        if ($permissions->contains(permission($uses))) return $next($request);
        return json(false, '没有操作权限', 403);
    }
}

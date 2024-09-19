<?php

namespace Kernel\Middleware;

use Kernel\Models\UserLog;
use Closure;
use Illuminate\Support\Arr;
use Laravel\Lumen\Http\Request;

class LogMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasHeader('dict')) return $next($request);
        $response = $next($request);
        if ($response->getStatusCode() === 200) {
            $route = $request->route();
            $uses = Arr::get($route[1], 'uses');
            UserLog::query()->create([
                'user_uuid' => user('uuid'),
                'action' => permission($uses),
                'params' => $request->all(),
                'status' => $response->getData(true)['result'] ?? 0
            ]);
        }
        return $response;
    }
}

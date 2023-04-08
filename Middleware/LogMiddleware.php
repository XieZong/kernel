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
            UserLog::create([
                'user_uuid' => user('uuid'),
                'action' => base64_encode($uses),
                'params' => $request->all(),
                'status' => $response->getData(true)['result']
            ]);
        }
        return $response;
    }
}

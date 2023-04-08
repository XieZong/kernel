<?php

namespace Kernel\Controllers;

use Kernel\BaseRoute;
use Kernel\Models\LoginLog;
use Kernel\Response;

class LoginLogController extends BaseRoute
{
    public function index(): Response
    {
        $data = LoginLog::latest()
            ->when(request()->has('ip'), function ($query) {
                $query->where('ip', 'like', '%' . request('ip') . '%');
            })
            ->when(request()->has('username'), function ($query) {
                $query->where('username', 'like', '%' . request('username') . '%');
            })
            ->when(request()->has('created_at'), function ($query) {
                $query
                    ->whereDate('created_at', '>=', head(request('created_at')))
                    ->whereDate('created_at', '<=', last(request('created_at')));
            })
            ->paginate(10);
        return json()->data($data->items())->total($data->total())->params(request()->all());
    }
}

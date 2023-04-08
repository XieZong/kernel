<?php

namespace Kernel\Controllers;

use Kernel\Generator;
use Kernel\Models\User;
use Kernel\Models\UserLog;

class UserLogController
{
    public function index()
    {
        $data = UserLog::latest()
            ->when(request()->has('user_uuid'), function ($query) {
                $query->where('user_uuid', request('user_uuid'));
            })
            ->when(request()->has('action'), function ($query) {
                $query->where('action', request('action'));
            })
            ->when(request()->has('created_at'), function ($query) {
                $query
                    ->whereDate('created_at', '>=', head(request('created_at')))
                    ->whereDate('created_at', '<=', last(request('created_at')));
            })
            ->when(request()->has('status'), function ($query) {
                $query->where('status', request('status'));
            })
            ->paginate(10);
        return json()
            ->data($data->items())
            ->total($data->total())
            ->params(request()->all())
            ->dict([
                'user' => User::get(['uuid as value', 'name as label']),
                'action' => Generator::generateLogsData()
            ]);
    }
}

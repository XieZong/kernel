<?php

namespace Kernel\Controllers;

use Exception;
use Illuminate\Support\Facades\DB;
use Kernel\BaseController;
use Kernel\Generator;
use Kernel\Models\Role;
use Kernel\Models\User;
use Kernel\Response;

class RoleController extends BaseController
{
    public array $rules = ['title' => 'required'];
    public array $messages = ['title.required' => '请输入名称'];

    public function index(): Response
    {
        $data = Role::latest()
            ->with('users:uuid')
            ->when(request()->has('title'), function ($query) {
                $query->where('title', 'like', '%' . request('title') . '%');
            })
            ->paginate(10);
        return json()
            ->data($data->map(fn($item) => ['users' => $item->users->modelKeys()] + $item->toArray()))
            ->total($data->total())
            ->params(request()->all())
            ->dict([
                'user' => User::get(['uuid as value', 'name as label']),
                'permission' => Generator::generatePermissionsData()
            ]);
    }

    public function store(): Response
    {
        if ($message = $this->validator()) return json(false, $message);
        try {
            DB::transaction(function () {
                $model = Role::create([
                    'title' => request('title'),
                    'permissions' => request('permissions', []),
                    'description' => request('description', '')
                ]);
                request('users') && $model->users()->sync(request('users'));
            });
            return json();
        } catch (Exception) {
            return json(false);
        }
    }

    public function update(): Response
    {
        if ($message = $this->validator()) return json(false, $message);
        if (!$model = Role::find(request('uuid'))) return json(false, '数据不存在');
        try {
            DB::transaction(function () use ($model) {
                $model->update([
                    'title' => request('title'),
                    'permissions' => request('permissions', []),
                    'description' => request('description', '')
                ]);
                $model->users()->sync(request('users', []));
            });
            return json();
        } catch (Exception) {
            return json(false);
        }
    }

    public function destroy(): Response
    {
        return json(Role::destroy(request('uuid')));
    }
}

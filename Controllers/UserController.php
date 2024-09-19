<?php

namespace Kernel\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Kernel\BaseController;
use Kernel\Generator;
use Kernel\Models\LoginLog;
use Kernel\Models\Role;
use Kernel\Models\User;
use Kernel\Response;

class UserController extends BaseController
{
    public function login(): Response
    {
        $this->rules = [
            'username' => 'required',
            'password' => 'required'
        ];
        $this->messages = [
            'username.required' => '请输入账号',
            'password.required' => '请输入密码'
        ];
        if ($message = $this->validator()) return json(false, $message);

        $user = User::where('username', request('username'))->first();
        $result = $user && Hash::check(request('password'), $user->password);
        $timeout = config('kernel.token_timeout');
        $exp_time = $timeout ? Carbon::now()->add($timeout)->timestamp : null;
        LoginLog::create(['ip' => request()->ip(), 'username' => request('username'), 'status' => $result]);
        if ($result) return json()->data($user->createToken($exp_time));
        return json(false, '账号密码错误');
    }

    public function info(): Response
    {
        return json()->data([
            'name' => user('name'),
            'is_admin' => user('is_admin'),
            'permissions' => user('all_permissions')
        ]);
    }

    public function logout(): Response
    {
        return json(user()->currentToken()->delete());
    }

    public function passwd(): Response
    {
        $this->rules = [
            'password' => 'required',
            'new_password' => 'required'
        ];
        $this->messages = [
            'password.required' => '请输入原密码',
            'new_password.required' => '请输入新密码'
        ];
        if ($message = $this->validator()) return json(false, $message);

        if (!Hash::check(request('password'), user('password'))) return json(false, '原密码错误');
        if (request('new_password') !== request('confirm_password')) return json(false, '两次密码输入不一致');
        if (user()->update(['password' => Hash::make(request('new_password'))])) {
            user()->tokens()->delete();
            return json();
        }
        return json(false);
    }

    public function index(): Response
    {
        $data = User::latest()
            ->with('roles:uuid,permissions')
            ->when(request()->has('name'), function ($query) {
                $query->where('name', 'like', '%' . request('name') . '%');
            })
            ->when(request()->has('username'), function ($query) {
                $query->where('username', 'like', '%' . request('username') . '%');
            })
            ->paginate(10);
        return json()
            ->total($data->total())
            ->params(request()->all())
            ->data($data->map(fn($item) => [
                    'roles' => $item->roles->modelKeys(),
                    'all_permissions' => $item->all_permissions,
                    'is_admin' => $item->is_admin,
                ] + $item->toArray())
            )
            ->dict([
                'role' => Role::get(['uuid as value', 'title as label']),
                'permission' => Generator::generatePermissionsData()
            ]);
    }

    public function store(): Response
    {
        $this->rules = [
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required',
        ];
        $this->messages = [
            'name.required' => '请输入名城',
            'username.required' => '请输入账号',
            'username.unique' => '账号已存在',
            'password.required' => '请输入密码'
        ];
        if ($message = $this->validator()) return json(false, $message);
        try {
            DB::transaction(function () {
                $model = User::create([
                    'name' => request('name'),
                    'username' => request('username'),
                    'password' => Hash::make(request('password')),
                    'permissions' => request('permissions', [])
                ]);
                if (request('roles')) $model->roles()->sync(request('roles'));
            });
            return json();
        } catch (Exception) {
            return json(false);
        }
    }

    public function update(): Response
    {
        if (!$model = User::find(request('uuid'))) return json(false, '数据不存在');
        $this->rules = [
            'name' => 'required',
            'username' => ['required', Rule::unique('users')->ignore($model)]
        ];
        $this->messages = [
            'name.required' => '请输入名城',
            'username.required' => '请输入账号',
            'username.unique' => '账号已存在'
        ];
        if ($message = $this->validator()) return json(false, $message);
        try {
            DB::transaction(function () use ($model) {
                $data = [
                    'name' => request('name'),
                    'username' => request('username'),
                    'permissions' => request('permissions', [])
                ];
                if (request()->has('password')) {
                    $data['password'] = Hash::make(request('password'));
                    $model->tokens()->delete();
                }
                $model->update($data);
                $model->roles()->sync(request('roles', []));
            });
            return json();
        } catch (Exception) {
            return json(false);
        }
    }

    public function destroy(): Response
    {
        if (!$model = User::find(request('uuid'))) return json(false, '数据不存在');
        try {
            DB::transaction(function () use ($model) {
                $model->tokens()->delete();
                $model->delete();
            });
            return json();
        } catch (Exception) {
            return json(false);
        }
    }
}

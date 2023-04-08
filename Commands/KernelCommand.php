<?php

namespace Kernel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Kernel\Models\User;

class KernelCommand extends Command
{
    protected $signature = 'kernel:admin';

    protected $description = '初始化管理员';

    public function handle(): void
    {
        if (!User::count()) {
            $password = Str::random();
            User::create([
                'name' => '管理员',
                'username' => 'admin',
                'password' => Hash::make($password),
                'permissions' => [],
            ]);
            $this->info('账号：admin 密码：' . $password);
        }
    }
}

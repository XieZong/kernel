<?php

namespace Kernel\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Kernel\Models\User;

class KernelAdminCommand extends Command
{
    protected $signature = 'kernel:admin';

    protected $description = '初始化管理员';

    public function handle(): void
    {
        if (!User::query()->count()) {
            $username = head(admin());
            $password = Str::random();
            User::query()->create([
                'name' => '管理员',
                'username' => $username,
                'password' => Hash::make($password),
                'permissions' => [],
            ]);
            $this->info("账号:{$username} 密码:{$password}");
        }
    }
}

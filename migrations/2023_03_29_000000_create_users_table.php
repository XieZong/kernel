<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public string $table = 'users';

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('name')->comment('姓名');
            $table->string('username')->unique()->comment('账号');
            $table->string('password')->comment('密码');
            $table->json('permissions')->comment('权限集');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}

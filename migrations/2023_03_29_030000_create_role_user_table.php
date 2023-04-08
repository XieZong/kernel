<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleUserTable extends Migration
{
    public string $table = 'role_user';

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid('user_uuid');
            $table->uuid('role_uuid');
            $table->primary(['user_uuid', 'role_uuid']);
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLogsTable extends Migration
{
    public string $table = 'user_logs';

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->uuid('user_uuid')->comment('用户uuid');
            $table->string('action');
            $table->json('params');
            $table->unsignedTinyInteger('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}

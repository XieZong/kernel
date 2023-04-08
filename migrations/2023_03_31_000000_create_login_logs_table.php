<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoginLogsTable extends Migration
{
    public string $table = 'login_logs';

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->string('username');
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

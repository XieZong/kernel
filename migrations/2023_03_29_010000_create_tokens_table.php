<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private string $table = 'tokens';

    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid()->primary();
            $table->uuid('user_uuid')->comment('用户uuid');
            $table->unsignedInteger('exp_time')->nullable()->comment('过期时间 null永不过期');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->table);
    }
};

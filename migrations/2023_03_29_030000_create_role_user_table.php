<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private string $table = 'role_user';

    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid('user_uuid');
            $table->uuid('role_uuid');
            $table->primary(['user_uuid', 'role_uuid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->table);
    }
};

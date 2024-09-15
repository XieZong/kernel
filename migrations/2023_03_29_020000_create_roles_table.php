<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private string $table = 'roles';

    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('title')->comment('名称');
            $table->string('description')->default('')->comment('描述');
            $table->json('permissions')->comment('权限集');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->table);
    }
};

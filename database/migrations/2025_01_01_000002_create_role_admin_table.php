<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('role_admin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();
            $table->boolean('status')->default(1);
            $table->unique(['role_id', 'admin_id']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('role_admin');
    }
};

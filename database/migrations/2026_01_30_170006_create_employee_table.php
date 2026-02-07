<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employeeUsers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appUserId')->constrained('appUsers')->onDelete('cascade');
            $table->string('password', 64);
            $table->boolean('isInactive')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('employeeUserRoles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employeeUserId')->constrained('employeeUsers')->onDelete('cascade');
            $table->foreignId('roleId')->constrained('roles')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['employeeUserId', 'roleId']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employeeUserRoles');
        Schema::dropIfExists('employeeUsers');
    }
};
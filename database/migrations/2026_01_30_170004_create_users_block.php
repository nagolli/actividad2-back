<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appUsers', function (Blueprint $table) {
            $table->id();
            $table->string('email', 64)->unique();
            $table->string('name', 64)->nullable();
            $table->string('surname', 128)->nullable();
            $table->string('phone', 32)->nullable();
            $table->timestamps();
        });

        Schema::create('clientUsers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appUserId')->constrained('appUsers')->onDelete('cascade');
            $table->string('password', 64);
            $table->integer('level')->default(1);
            $table->integer('points')->default(0);
            $table->timestamps();
        });

        Schema::create('employeeUsers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appUserId')->constrained('appUsers')->onDelete('cascade');
            $table->string('password', 64);
            $table->boolean('isInactive')->default(false);
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('employeeUserRoles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employeeUserId')->constrained('employeeUsers')->onDelete('cascade');
            $table->foreignId('roleId')->constrained('roles')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['employeeUserId', 'roleId']);
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('description')->unique();
            $table->timestamps();
        });

        Schema::create('rolesPermissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('roleId')->constrained('roles')->onDelete('cascade');
            $table->foreignId('permissionId')->constrained('permissions')->onDelete('cascade');
            $table->unsignedTinyInteger('permissionLevel')->default(0);
            $table->timestamps();
            $table->unique(['roleId', 'permissionId']);
        });
        DB::statement("ALTER TABLE `rolesPermissions` ADD CONSTRAINT `chk_permission_level` CHECK (`permissionLevel` IN (0,1,2,3))");

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('country', 64);
            $table->string('province', 64);
            $table->string('postalCode', 8);
            $table->string('city', 64);
            $table->string('street', 128);
            $table->string('number');
            $table->string('floor')->nullable();
            $table->string('door')->nullable();
            $table->string('staircase')->nullable();
            $table->timestamps();
        });

        Schema::create('appUserAddresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('addressId')->constrained('addresses')->onDelete('cascade');
            $table->foreignId('appUserId')->constrained('appUsers')->onDelete('cascade');
            $table->string('name', 128)->nullable();
            $table->timestamps();
            $table->unique(['addressId', 'appUserId']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appUserAddresses');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('rolesPermissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('employeeUserRoles');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('employeeUsers');
        Schema::dropIfExists('clientUsers');
        Schema::dropIfExists('appUsers');
    }
};
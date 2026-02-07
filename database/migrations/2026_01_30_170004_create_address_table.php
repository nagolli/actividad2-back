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
            $table->softDeletes();
        });

        Schema::create('appUserAddresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('addressId')->constrained('addresses')->onDelete('cascade');
            $table->foreignId('appUserId')->constrained('appUsers')->onDelete('cascade');
            $table->string('name', 128)->nullable();
            $table->timestamps();
            $table->softDeletes();
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
    }
};
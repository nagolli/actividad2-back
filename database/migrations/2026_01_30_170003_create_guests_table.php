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
        Schema::create('appUsers', function (Blueprint $table) {
            $table->id();
            $table->string('email', 64)->unique();
            $table->string('name', 64)->nullable();
            $table->string('surname', 128)->nullable();
            $table->string('phone', 32)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appUsers');
    }
};
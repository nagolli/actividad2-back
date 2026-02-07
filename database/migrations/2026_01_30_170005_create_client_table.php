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
        Schema::create('clientUsers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appUserId')->constrained('appUsers')->onDelete('cascade');
            $table->string('password', 64);
            $table->integer('level')->default(1);
            $table->integer('points')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientUsers');
    }
};
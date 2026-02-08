<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('review', function (Blueprint $table) {
            $table->unsignedBigInteger('productId');
            $table->string('email', 64);

            $table->text('review');
            $table->integer('rating');

            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('deletedAt')->nullable();

            $table->primary(['productId', 'email']);

            $table->foreign('productId')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

            $table->foreign('email')
                ->references('email')
                ->on('appUsers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review');
    }
};

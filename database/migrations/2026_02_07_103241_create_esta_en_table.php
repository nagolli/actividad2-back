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
        Schema::create('esta_en', function (Blueprint $table) {

            //$table->id();
            $table->unsignedBigInteger('orderId');
            $table->unsignedBigInteger('productId');
            $table->integer('quantity');
            $table->decimal('price', 8, 2);
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('deletedAt')->nullable();

            $table->primary(['orderId', 'productId']);

            $table->foreign('orderId')
              ->references('id')
              ->on('orders')
              ->onDelete('cascade');

            $table->foreign('productId')
              ->references('id')
              ->on('products')
              ->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esta_en');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date')->useCurrent();
            $table->string('state')->useCurrent();
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('addressId');
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('deletedAt')->nullable();

            $table->foreign('userId')
                ->references(columns: 'id')
                ->on('appUsers')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            // Clave ajena a tabla direccion
            $table->foreign('addressId')
                ->references('id')
                ->on('addresses')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

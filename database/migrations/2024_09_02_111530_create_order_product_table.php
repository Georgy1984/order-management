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
        Schema::create('order_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->index()->constrained();
            $table->foreignId('product_id')->index()->constrained();
            //$table->unique(['order_id', 'product_id']); добавить к миграции, чтобы исключить повторное добавление товара
            //подумать как реализовать это на уровне контроллера.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_product');
    }
};

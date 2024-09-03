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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            //чтобы ограничить 'status' определенными значениями из списка.
            $table->enum('status', ['created', 'paid', 'completed', 'failed', 'cancelled']);
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('completed_at')->nullable();


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

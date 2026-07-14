<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('order_id');
            $table->unsignedInteger('table_id')->nullable();

            $table->string('order_code')->unique();
            $table->string('customer_name')->nullable();

            $table->enum('status', [
                'menunggu',
                'diproses',
                'dibatalkan',
                'selesai'
            ])->default('menunggu');

            $table->decimal('total_amount', 12, 2)->default(0);

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->foreign('table_id')
                  ->references('table_id')
                  ->on('cafe_tables')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
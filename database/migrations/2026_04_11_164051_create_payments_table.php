<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function ($table) {
            $table->increments('payment_id');

            $table->unsignedInteger('order_id');
            $table->unsignedInteger('admin_id')->nullable();

            $table->enum('payment_method', ['cash', 'credit_card', 'qris']);

            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            $table->foreign('order_id')->references('order_id')->on('orders');
            $table->foreign('admin_id')->references('admin_id')->on('admins');
});
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
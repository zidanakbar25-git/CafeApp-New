<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_details', function ($table) {
            $table->increments('detail_id');

            $table->unsignedInteger('order_id');
            $table->unsignedInteger('menu_id');

            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('subtotal', 12, 2);

            

            $table->timestamps();

            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
            $table->foreign('menu_id')->references('menu_id')->on('menus');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
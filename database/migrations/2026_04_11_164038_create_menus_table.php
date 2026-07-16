<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_detail_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('detail_id');        // <-- diubah dari unsignedBigInteger
            $table->string('group_name');
            $table->string('input_type');
            $table->string('option_name')->nullable();
            $table->text('text_value')->nullable();
            $table->timestamps();

            $table->foreign('detail_id')
                  ->references('detail_id')->on('order_details')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_detail_options');
    }
};
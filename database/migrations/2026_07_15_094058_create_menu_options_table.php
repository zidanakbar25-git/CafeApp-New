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
    Schema::create('menu_options', function (Blueprint $table) {
        $table->id('option_id');
        $table->unsignedBigInteger('group_id');
        $table->string('name');
        $table->unsignedInteger('sort_order')->default(0);
        $table->boolean('is_active')->default(true);
        $table->timestamps();

        $table->foreign('group_id')
              ->references('group_id')->on('menu_option_groups')
              ->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::dropIfExists('menu_options');
}
};

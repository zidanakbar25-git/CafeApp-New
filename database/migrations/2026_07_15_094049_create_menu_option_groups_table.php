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
    Schema::create('menu_option_groups', function (Blueprint $table) {
        $table->id('group_id');
        $table->unsignedInteger('menu_id');
        $table->string('name');
        $table->enum('input_type', ['radio', 'checkbox', 'text']);
        $table->boolean('is_required')->default(false);
        $table->unsignedTinyInteger('min_select')->nullable();
        $table->unsignedTinyInteger('max_select')->nullable();
        $table->string('placeholder')->nullable();
        $table->unsignedInteger('sort_order')->default(0);
        $table->timestamps();

        $table->foreign('menu_id')
              ->references('menu_id')->on('menus')
              ->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::dropIfExists('menu_option_groups');
}
};

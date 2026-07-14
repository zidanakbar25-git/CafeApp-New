<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_categories', function ($table) {
            $table->increments('sub_id');
            $table->unsignedInteger('category_id');
            $table->string('name');

            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('cascade');

            $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_categories');
    }
};
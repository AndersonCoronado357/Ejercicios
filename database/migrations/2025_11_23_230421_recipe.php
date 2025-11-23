<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('category');
            $table->string('difficulty');
            $table->integer('prep_time');
            $table->integer('cook_time');
            $table->integer('servings');
            $table->text('ingredients');
            $table->text('instructions');
            $table->string('image_url')->nullable();
            $table->boolean('is_favorite')->default(false);
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};

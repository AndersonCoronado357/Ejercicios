<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memory_games', function (Blueprint $table) {
            $table->id();
            $table->string('player_name');
            $table->string('difficulty'); // easy, medium, hard
            $table->integer('moves');
            $table->integer('time'); // en segundos
            $table->integer('score');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memory_games');
    }
};

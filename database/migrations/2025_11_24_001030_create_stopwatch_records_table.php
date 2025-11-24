<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stopwatch_records', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->integer('time_milliseconds');
            $table->json('laps')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stopwatch_records');
    }
};

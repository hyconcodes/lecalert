<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lectures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('course_title');
            $table->string('course_code');
            $table->date('lecture_date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->string('venue');
            $table->integer('reminder_minutes')->default(30);
            $table->timestamps();

            $table->index(['user_id', 'lecture_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lectures');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->integer('default_reminder_minutes')->default(30);
            $table->boolean('enable_reminders')->default(true);
            $table->boolean('enable_browser_notifications')->default(false);
            $table->boolean('enable_sound_notifications')->default(false);
            $table->enum('time_format', ['12h', '24h'])->default('12h');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};

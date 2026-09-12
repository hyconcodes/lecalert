<?php

namespace Database\Factories;

use App\Models\Lecture;
use App\Models\ReminderLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReminderLog>
 */
class ReminderLogFactory extends Factory
{
    protected $model = ReminderLog::class;

    public function definition(): array
    {
        return [
            'lecture_id' => Lecture::factory(),
            'user_id' => User::factory(),
            'scheduled_reminder_time' => fake()->dateTimeBetween('-1 day', '+1 day'),
            'status' => fake()->randomElement(['sent', 'failed', 'pending']),
            'sent_at' => fake()->optional(0.8)->dateTimeBetween('-1 day', 'now'),
        ];
    }

    public function sent(): static
    {
        return $this->state(fn () => [
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn () => [
            'status' => 'failed',
            'sent_at' => null,
        ]);
    }
}

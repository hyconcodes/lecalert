<?php

namespace Database\Factories;

use App\Models\Lecture;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Notification>
 */
class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'lecture_id' => Lecture::factory(),
            'title' => fake()->sentence(4),
            'message' => fake()->paragraph(1),
            'type' => fake()->randomElement(['reminder', 'system']),
            'is_read' => false,
        ];
    }

    public function read(): static
    {
        return $this->state(fn () => [
            'is_read' => true,
        ]);
    }

    public function reminder(): static
    {
        return $this->state(fn () => [
            'type' => 'reminder',
        ]);
    }

    public function system(): static
    {
        return $this->state(fn () => [
            'type' => 'system',
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Models\Lecture;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lecture>
 */
class LectureFactory extends Factory
{
    protected $model = Lecture::class;

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-1 month', '+2 months');
        $startTime = $this->faker->time('H:i');
        $endTime = $this->faker->time('H:i', '+2 hours');

        return [
            'user_id' => User::factory(),
            'course_title' => fake()->words(3, true),
            'course_code' => strtoupper(fake()->lexify('???')).fake()->numberBetween(100, 499),
            'lecture_date' => $startDate->format('Y-m-d'),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'venue' => fake()->city().' Building, Room '.fake()->numberBetween(100, 500),
            'reminder_minutes' => fake()->randomElement([10, 15, 30, 60, 120]),
        ];
    }

    public function upcoming(): static
    {
        return $this->state(fn () => [
            'lecture_date' => fake()->dateTimeBetween('+1 day', '+2 weeks')->format('Y-m-d'),
        ]);
    }

    public function today(): static
    {
        return $this->state(fn () => [
            'lecture_date' => now()->toDateString(),
            'start_time' => now()->addHours(2)->format('H:i:s'),
            'end_time' => now()->addHours(3)->format('H:i:s'),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'lecture_date' => fake()->dateTimeBetween('-2 weeks', '-1 day')->format('Y-m-d'),
        ]);
    }
}

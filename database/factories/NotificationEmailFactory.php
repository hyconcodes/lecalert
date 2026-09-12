<?php

namespace Database\Factories;

use App\Models\NotificationEmail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<NotificationEmail>
 */
class NotificationEmailFactory extends Factory
{
    protected $model = NotificationEmail::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'email' => fake()->unique()->safeEmail(),
            'is_verified' => false,
            'verification_code' => strtoupper(Str::random(6)),
            'verified_at' => null,
        ];
    }

    public function verified(): static
    {
        return $this->state(fn () => [
            'is_verified' => true,
            'verified_at' => now(),
            'verification_code' => null,
        ]);
    }
}

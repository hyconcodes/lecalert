<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserSetting>
 */
class UserSettingFactory extends Factory
{
    protected $model = UserSetting::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'default_reminder_minutes' => fake()->randomElement([10, 15, 30, 60, 120]),
            'enable_reminders' => true,
            'enable_browser_notifications' => false,
            'enable_sound_notifications' => false,
            'time_format' => fake()->randomElement(['12h', '24h']),
        ];
    }
}

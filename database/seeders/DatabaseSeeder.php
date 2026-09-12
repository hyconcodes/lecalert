<?php

namespace Database\Seeders;

use App\Models\Lecture;
use App\Models\User;
use App\Models\UserSetting;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Demo Student',
            'email' => 'demo.student@bouesti.edu.ng',
            'password' => 'password',
        ]);

        UserSetting::create([
            'user_id' => $user->id,
            'default_reminder_minutes' => 30,
            'enable_reminders' => true,
            'enable_browser_notifications' => false,
            'enable_sound_notifications' => false,
            'time_format' => '12h',
        ]);

        $today = now();

        Lecture::create([
            'user_id' => $user->id,
            'course_title' => 'Artificial Intelligence',
            'course_code' => 'CSC 401',
            'lecture_date' => $today->copy()->next(Carbon::MONDAY)->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '12:00',
            'venue' => 'Computer Science Lab',
            'reminder_minutes' => 30,
        ]);

        Lecture::create([
            'user_id' => $user->id,
            'course_title' => 'Software Engineering',
            'course_code' => 'CSC 403',
            'lecture_date' => $today->copy()->next(Carbon::TUESDAY)->format('Y-m-d'),
            'start_time' => '08:00',
            'end_time' => '10:00',
            'venue' => 'Lecture Hall A',
            'reminder_minutes' => 15,
        ]);

        Lecture::create([
            'user_id' => $user->id,
            'course_title' => 'Database Management',
            'course_code' => 'CSC 405',
            'lecture_date' => $today->copy()->next(Carbon::WEDNESDAY)->format('Y-m-d'),
            'start_time' => '13:00',
            'end_time' => '15:00',
            'venue' => 'ICT Building',
            'reminder_minutes' => 30,
        ]);
    }
}

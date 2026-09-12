<?php

namespace App\Services;

use App\Mail\LectureReminderMail;
use App\Models\Lecture;
use App\Models\Notification;
use App\Models\NotificationEmail;
use App\Models\ReminderLog;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class ReminderService
{
    /**
     * Check for upcoming lectures and trigger reminders.
     */
    public function checkUpcomingLectures(): void
    {
        $now = now();

        $lectures = Lecture::with('user.settings', 'user.notificationEmails')
            ->whereHas('user.settings', fn ($q) => $q->where('enable_reminders', true))
            ->where('lecture_date', '>=', $now->toDateString())
            ->get();

        foreach ($lectures as $lecture) {
            $this->processLectureReminder($lecture, $now);
        }
    }

    /**
     * Process reminder for a single lecture.
     */
    private function processLectureReminder(Lecture $lecture, Carbon $now): void
    {
        $lectureDateTime = $lecture->lecture_date->toDateTime()->setTime(
            (int) $lecture->start_time->format('H'),
            (int) $lecture->start_time->format('i'),
        );

        $reminderTime = $lectureDateTime->copy()->subMinutes($lecture->reminder_minutes);

        if ($now->lessThan($reminderTime) || $now->greaterThan($lectureDateTime)) {
            return;
        }

        $user = $lecture->user;

        if ($this->isAlreadySent($lecture, $user)) {
            return;
        }

        $this->createInAppNotification($user, $lecture);
        $this->sendEmailNotifications($user, $lecture);
        $this->logReminder($lecture, $user, $reminderTime);
    }

    /**
     * Check if a reminder has already been sent for this lecture.
     */
    public function isAlreadySent(Lecture $lecture, User $user): bool
    {
        return ReminderLog::where('lecture_id', $lecture->id)
            ->where('user_id', $user->id)
            ->where('status', 'sent')
            ->exists();
    }

    /**
     * Create an in-app notification for the user.
     */
    private function createInAppNotification(User $user, Lecture $lecture): void
    {
        Notification::create([
            'user_id' => $user->id,
            'lecture_id' => $lecture->id,
            'title' => 'Lecture Reminder',
            'message' => "Your {$lecture->course_code} - {$lecture->course_title} lecture is starting in {$lecture->reminder_minutes} minutes at {$lecture->venue}.",
            'type' => 'reminder',
        ]);
    }

    /**
     * Send email notifications to the user and their verified notification emails.
     */
    private function sendEmailNotifications(User $user, Lecture $lecture): void
    {
        Mail::to($user->email)->send(new LectureReminderMail($user, $lecture));

        $verifiedEmails = NotificationEmail::where('user_id', $user->id)
            ->where('is_verified', true)
            ->get();

        foreach ($verifiedEmails as $emailRecord) {
            Mail::to($emailRecord->email)->send(new LectureReminderMail($user, $lecture));
        }
    }

    /**
     * Log that a reminder has been sent.
     */
    private function logReminder(Lecture $lecture, User $user, Carbon $scheduledTime): void
    {
        ReminderLog::create([
            'lecture_id' => $lecture->id,
            'user_id' => $user->id,
            'scheduled_reminder_time' => $scheduledTime,
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * Calculate the reminder time for a lecture.
     */
    public function getReminderTime(Lecture $lecture): Carbon
    {
        $lectureDateTime = $lecture->lecture_date->toDateTime()->setTime(
            (int) $lecture->start_time->format('H'),
            (int) $lecture->start_time->format('i'),
        );

        return $lectureDateTime->subMinutes($lecture->reminder_minutes);
    }
}

<?php

namespace App\Console\Commands;

use App\Services\ReminderService;
use Illuminate\Console\Command;

class CheckReminders extends Command
{
    protected $signature = 'reminders:check';

    protected $description = 'Check for upcoming lectures and send reminders';

    public function handle(ReminderService $reminderService): int
    {
        $reminderService->checkUpcomingLectures();

        $this->info('Reminders checked successfully at '.now()->format('Y-m-d H:i:s'));

        return self::SUCCESS;
    }
}

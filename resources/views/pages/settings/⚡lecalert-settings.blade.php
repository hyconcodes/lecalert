<?php

use App\Models\UserSetting;
use Flux\Flux;
use Livewire\Component;

new class extends Component
{
    public $defaultReminderMinutes = 30;
    public $enableReminders = true;
    public $enableBrowserNotifications = false;
    public $enableSoundNotifications = false;
    public $timeFormat = '12h';

    public function mount(): void
    {
        $settings = auth()->user()->settings;
        if ($settings) {
            $this->defaultReminderMinutes = $settings->default_reminder_minutes;
            $this->enableReminders = $settings->enable_reminders;
            $this->enableBrowserNotifications = $settings->enable_browser_notifications;
            $this->enableSoundNotifications = $settings->enable_sound_notifications;
            $this->timeFormat = $settings->time_format;
        }
    }

    public function save(): void
    {
        UserSetting::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'default_reminder_minutes' => $this->defaultReminderMinutes,
                'enable_reminders' => $this->enableReminders,
                'enable_browser_notifications' => $this->enableBrowserNotifications,
                'enable_sound_notifications' => $this->enableSoundNotifications,
                'time_format' => $this->timeFormat,
            ],
        );

        Flux::toast(heading: 'Settings saved.', variant: 'success');
    }

};
?>

<div class="flex h-full w-full flex-1 flex-col gap-6 p-4 lg:p-6">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Settings</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Configure your reminder preferences and notification settings</p>
        </div>

        <form wire:submit="save" class="max-w-2xl space-y-6">
            {{-- Reminder Settings --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">Reminder Settings</h2>

                <div class="space-y-4">
                    <flux:field>
                        <flux:label>Default Reminder Time</flux:label>
                        <flux:select wire:model="defaultReminderMinutes">
                            <flux:select.option value="10">10 minutes before</flux:select.option>
                            <flux:select.option value="15">15 minutes before</flux:select.option>
                            <flux:select.option value="30">30 minutes before</flux:select.option>
                            <flux:select.option value="60">1 hour before</flux:select.option>
                        </flux:select>
                        <flux:text class="text-xs">This will be the default reminder time when adding new lectures.</flux:text>
                    </flux:field>

                    <div class="flex items-center justify-between rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
                        <div>
                            <p class="font-medium text-zinc-900 dark:text-white">Enable Reminders</p>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">Receive reminders before your lectures</p>
                        </div>
                        <flux:switch wire:model="enableReminders" />
                    </div>
                </div>
            </div>

            {{-- Notification Settings --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">Notification Settings</h2>

                <div class="space-y-3">
                    <div class="flex items-center justify-between rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
                        <div>
                            <p class="font-medium text-zinc-900 dark:text-white">Browser Notifications</p>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">Show desktop notifications in your browser</p>
                        </div>
                        <flux:switch wire:model="enableBrowserNotifications" />
                    </div>

                    <div class="flex items-center justify-between rounded-lg border border-zinc-200 p-4 dark:border-zinc-700">
                        <div>
                            <p class="font-medium text-zinc-900 dark:text-white">Sound Notifications</p>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">Play a sound when a reminder is triggered</p>
                        </div>
                        <flux:switch wire:model="enableSoundNotifications" />
                    </div>
                </div>
            </div>

            {{-- Display Settings --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">Display Settings</h2>

                <flux:field>
                    <flux:label>Time Format</flux:label>
                    <flux:select wire:model="timeFormat">
                        <flux:select.option value="12h">12-hour (1:00 PM)</flux:select.option>
                        <flux:select.option value="24h">24-hour (13:00)</flux:select.option>
                    </flux:select>
                </flux:field>
            </div>

            {{-- Notification Emails --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Notification Emails</h2>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Add up to 2 extra email addresses to receive lecture reminders</p>
                    </div>
                    <flux:button variant="outline" href="{{ route('notification-emails') }}" icon="envelope" wire:navigate>
                        Manage
                    </flux:button>
                </div>
            </div>

            <div class="flex justify-end">
                <flux:button variant="primary" type="submit" icon="check">
                    Save Settings
                </flux:button>
            </div>
        </form>
    </div>
</div>

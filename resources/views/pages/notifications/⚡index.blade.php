<?php

use Livewire\Component;

new class extends Component
{
    public $notifications = [];
    public $filter = 'all';

    public function mount(): void
    {
        $this->loadNotifications();
    }

    public function updatedFilter(): void
    {
        $this->loadNotifications();
    }

    public function loadNotifications(): void
    {
        $query = auth()->user()->notifications()->with('lecture');

        if ($this->filter === 'unread') {
            $query->where('is_read', false);
        } elseif ($this->filter === 'reminders') {
            $query->where('type', 'reminder');
        } elseif ($this->filter === 'system') {
            $query->where('type', 'system');
        }

        $this->notifications = $query->orderByDesc('created_at')->get()->toArray();
    }

    public function markAsRead(int $notificationId): void
    {
        auth()->user()->notifications()->where('id', $notificationId)->update(['is_read' => true]);
        $this->loadNotifications();
    }

    public function markAllAsRead(): void
    {
        auth()->user()->notifications()->where('is_read', false)->update(['is_read' => true]);
        $this->loadNotifications();
    }

    public function deleteNotification(int $notificationId): void
    {
        auth()->user()->notifications()->where('id', $notificationId)->delete();
        $this->loadNotifications();
    }

};
?>

<div class="flex h-full w-full flex-1 flex-col gap-6 p-4 lg:p-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Notifications</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">View your lecture reminders and system notifications</p>
            </div>
            @if(collect($notifications)->where('is_read', false)->count() > 0)
                <flux:button variant="outline" wire:click="markAllAsRead" icon="check-circle">
                    Mark All as Read
                </flux:button>
            @endif
        </div>

        {{-- Filters --}}
        <div class="flex gap-2">
            @foreach(['all' => 'All', 'unread' => 'Unread', 'reminders' => 'Reminders', 'system' => 'System'] as $value => $label)
                <button
                    wire:click="$set('filter', '{{ $value }}')"
                    class="inline-flex items-center rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ $filter === $value ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-400' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200 dark:bg-zinc-800 dark:text-zinc-400 dark:hover:bg-zinc-700' }}"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- Notifications List --}}
        @if(count($notifications) > 0)
            <div class="space-y-3">
                @foreach($notifications as $notification)
                    <div class="rounded-xl border border-zinc-200 bg-white p-4 transition-all hover:shadow-sm dark:border-zinc-700 dark:bg-zinc-900 {{ $notification['is_read'] ? '' : 'border-l-4 border-l-indigo-500' }}">
                        <div class="flex items-start gap-4">
                            <div class="flex size-10 shrink-0 items-center justify-center rounded-full {{ $notification['type'] === 'reminder' ? 'bg-indigo-100 dark:bg-indigo-500/20' : 'bg-zinc-100 dark:bg-zinc-800' }}">
                                @if($notification['type'] === 'reminder')
                                    <flux:icon.bell class="size-5 text-indigo-600 dark:text-indigo-400" />
                                @else
                                    <flux:icon.information-circle class="size-5 text-zinc-500 dark:text-zinc-400" />
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $notification['title'] }}</h3>
                                    @if(!$notification['is_read'])
                                        <span class="size-2 rounded-full bg-indigo-500"></span>
                                    @endif
                                </div>
                                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">{{ $notification['message'] }}</p>
                                <p class="mt-2 text-xs text-zinc-400 dark:text-zinc-500">{{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}</p>
                            </div>

                            <div class="flex items-center gap-1">
                                @if(!$notification['is_read'])
                                    <flux:button icon="check" variant="ghost" size="sm" wire:click="markAsRead({{ $notification['id'] }})" title="Mark as read">
                                    </flux:button>
                                @endif
                                <flux:button icon="trash" variant="ghost" size="sm" color="red" wire:click="deleteNotification({{ $notification['id'] }})" title="Delete">
                                </flux:button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <x-empty-state
                title="No Notifications"
                description="You don't have any notifications yet. They'll appear here when your lectures are approaching."
                icon="bell"
            />
        @endif
    </div>
</div>

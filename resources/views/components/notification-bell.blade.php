<?php

use Livewire\Component;

new class extends Component
{
    public $showDropdown = false;
    public $notifications = [];
    public $unreadCount = 0;

    public function mount(): void
    {
        $this->loadNotifications();
    }

    public function loadNotifications(): void
    {
        $user = auth()->user();
        $this->unreadCount = $user->notifications()->where('is_read', false)->count();
        $this->notifications = $user->notifications()
            ->with('lecture')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function toggleDropdown(): void
    {
        $this->showDropdown = !$this->showDropdown;
        if ($this->showDropdown) {
            $this->loadNotifications();
        }
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

};
?>

<div class="relative" x-data="{ open: false }" @click.outside="open = false; $wire.set('showDropdown', false)">
    <button
        wire:click="toggleDropdown"
        @click="open = !open"
        class="relative p-2 text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200"
    >
        <flux:icon.bell class="size-5" />
        @if($unreadCount > 0)
            <span class="absolute -top-0.5 -right-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-medium text-white">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-80 rounded-xl border border-zinc-200 bg-white shadow-lg dark:border-zinc-700 dark:bg-zinc-900"
        style="display: none"
    >
        <div class="flex items-center justify-between border-b border-zinc-100 px-4 py-3 dark:border-zinc-700">
            <h3 class="text-sm font-semibold text-zinc-900 dark:text-white">Notifications</h3>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-xs text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                    Mark all read
                </button>
            @endif
        </div>

        <div class="max-h-80 overflow-y-auto">
            @forelse($notifications as $notification)
                <div
                    wire:click="markAsRead({{ $notification['id'] }})"
                    class="flex gap-3 px-4 py-3 hover:bg-zinc-50 dark:hover:bg-zinc-800 cursor-pointer {{ $notification['is_read'] ? '' : 'bg-indigo-50/50 dark:bg-indigo-500/5' }}"
                >
                    <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-500/20">
                        <flux:icon.bell class="size-4 text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $notification['title'] }}</p>
                        <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400 line-clamp-2">{{ $notification['message'] }}</p>
                        <p class="mt-1 text-[11px] text-zinc-400 dark:text-zinc-500">{{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}</p>
                    </div>
                    @if(!$notification['is_read'])
                        <div class="mt-2 size-2 shrink-0 rounded-full bg-indigo-500"></div>
                    @endif
                </div>
            @empty
                <div class="px-4 py-8 text-center">
                    <flux:icon.bell class="mx-auto size-8 text-zinc-300 dark:text-zinc-600" />
                    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">No notifications yet</p>
                </div>
            @endforelse
        </div>

        <div class="border-t border-zinc-100 px-4 py-2 dark:border-zinc-700">
            <a href="{{ route('notifications.index') }}" class="block text-center text-xs font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400" wire:navigate>
                View all notifications
            </a>
        </div>
    </div>
</div>

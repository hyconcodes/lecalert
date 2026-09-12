<?php

namespace App\Livewire;

use Livewire\Component;

class NotificationCount extends Component
{
    public int $count = 0;

    public function mount(): void
    {
        $this->refreshCount();
    }

    public function refreshCount(): void
    {
        $this->count = auth()->check() ? auth()->user()->unread_notifications_count : 0;
    }

    public function getListeners(): array
    {
        return [
            'notificationUpdated' => 'refreshCount',
        ];
    }

    public function render()
    {
        return <<<'blade'
            <span>
                @if($count > 0)
                    <flux:badge color="red" size="sm">{{ $count > 9 ? '9+' : $count }}</flux:badge>
                @endif
            </span>
        blade;
    }
}

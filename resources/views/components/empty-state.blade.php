@props(['title' => 'No data yet', 'description' => '', 'icon' => 'inbox', 'actionLabel' => '', 'actionUrl' => ''])

@php
    $iconPaths = [
        'inbox' => '<path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v13.5A1.5 1.5 0 0 0 3.75 21Z" />',
        'bell' => '<path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />',
        'calendar-date-range' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />',
    ];
@endphp

<div class="flex flex-col items-center justify-center py-16 text-center">
    <div class="flex size-16 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 text-zinc-400 dark:text-zinc-500">
            {!! $iconPaths[$icon] ?? $iconPaths['inbox'] !!}
        </svg>
    </div>
    <h3 class="mt-4 text-lg font-semibold text-zinc-900 dark:text-white">{{ $title }}</h3>
    @if($description)
        <p class="mt-2 max-w-sm text-sm text-zinc-500 dark:text-zinc-400">{{ $description }}</p>
    @endif
    @if($actionLabel && $actionUrl)
        <flux:button variant="primary" href="{{ $actionUrl }}" class="mt-6" icon="plus" wire:navigate>
            {{ $actionLabel }}
        </flux:button>
    @endif
    {{ $slot }}
</div>

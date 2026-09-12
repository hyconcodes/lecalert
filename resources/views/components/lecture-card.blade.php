@props(['lecture'])

@php
    $status = $lecture->status;
    $statusConfig = match($status) {
        'upcoming' => ['label' => 'Upcoming', 'color' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400'],
        'today' => ['label' => 'Today', 'color' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400'],
        'ongoing' => ['label' => 'Ongoing', 'color' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400'],
        'completed' => ['label' => 'Completed', 'color' => 'bg-zinc-100 text-zinc-600 dark:bg-zinc-500/20 dark:text-zinc-400'],
    };
@endphp

<div class="group relative rounded-xl border border-zinc-200 bg-white p-5 transition-all hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-zinc-600">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center rounded-lg bg-indigo-100 px-3 py-1 text-sm font-bold text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-400">
                    {{ $lecture->course_code }}
                </span>
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusConfig['color'] }}">
                    {{ $statusConfig['label'] }}
                </span>
            </div>

            <h3 class="mt-2 text-lg font-semibold text-zinc-900 dark:text-white">
                {{ $lecture->course_title }}
            </h3>

            <div class="mt-3 flex flex-wrap gap-4 text-sm text-zinc-500 dark:text-zinc-400">
                <span class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" /></svg>
                    {{ $lecture->lecture_date->format('l, M j, Y') }}
                </span>
                <span class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    {{ $lecture->start_time_formatted }}{{ $lecture->end_time ? ' - '.$lecture->end_time_formatted : '' }}
                </span>
                <span class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                    {{ $lecture->venue }}
                </span>
            </div>

            <div class="mt-2 flex items-center gap-1.5 text-xs text-zinc-400 dark:text-zinc-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>
                Reminder: {{ $lecture->reminder_minutes }} min before
            </div>
        </div>

        <div class="flex items-center gap-1 opacity-0 transition-opacity group-hover:opacity-100">
            <flux:button icon="pencil" variant="ghost" size="sm" href="{{ route('lectures.edit', $lecture) }}" wire:navigate>
                Edit
            </flux:button>
            <flux:button icon="trash" variant="ghost" size="sm" color="red" wire:click="confirmDelete({{ $lecture->id }})">
                Delete
            </flux:button>
        </div>
    </div>
</div>

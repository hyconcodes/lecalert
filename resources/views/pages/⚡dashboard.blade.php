<?php

use App\Models\Lecture;
use Livewire\Component;

new class extends Component
{
    public $totalLectures = 0;
    public $todayLectures = 0;
    public $upcomingLectures = 0;
    public $completedLectures = 0;
    public $nextLecture = null;
    public $upcomingList = [];
    public $recentNotifications = [];
    public $nextLectureCountdown = '';

    public function mount(): void
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData(): void
    {
        $userId = auth()->id();

        $this->totalLectures = Lecture::where('user_id', $userId)->count();
        $this->todayLectures = Lecture::where('user_id', $userId)
            ->whereDate('lecture_date', now()->toDateString())
            ->count();

        $this->upcomingLectures = Lecture::where('user_id', $userId)
            ->where(function ($q) {
                $q->whereDate('lecture_date', '>', now()->toDateString())
                    ->orWhere(function ($q2) {
                        $q2->whereDate('lecture_date', '=', now()->toDateString())
                            ->where('start_time', '>', now()->format('H:i:s'));
                    });
            })
            ->count();

        $this->completedLectures = Lecture::where('user_id', $userId)
            ->where(function ($q) {
                $q->whereDate('lecture_date', '<', now()->toDateString())
                    ->orWhere(function ($q2) {
                        $q2->whereDate('lecture_date', '=', now()->toDateString())
                            ->where('end_time', '<', now()->format('H:i:s'));
                    });
            })
            ->count();

        $this->nextLecture = Lecture::where('user_id', $userId)
            ->where(function ($q) {
                $q->whereDate('lecture_date', '>', now()->toDateString())
                    ->orWhere(function ($q2) {
                        $q2->whereDate('lecture_date', '=', now()->toDateString())
                            ->where('start_time', '>', now()->format('H:i:s'));
                    });
            })
            ->orderBy('lecture_date')
            ->orderBy('start_time')
            ->first()
            ?->toArray();

        $this->upcomingList = Lecture::where('user_id', $userId)
            ->where(function ($q) {
                $q->whereDate('lecture_date', '>', now()->toDateString())
                    ->orWhere(function ($q2) {
                        $q2->whereDate('lecture_date', '=', now()->toDateString())
                            ->where('start_time', '>', now()->format('H:i:s'));
                    });
            })
            ->orderBy('lecture_date')
            ->orderBy('start_time')
            ->limit(5)
            ->get()
            ->toArray();

        $this->recentNotifications = auth()->user()->notifications()
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->toArray();

        $this->updateCountdown();
    }

    public function updateCountdown(): void
    {
        if (! $this->nextLecture) {
            $this->nextLectureCountdown = '';
            return;
        }

        $lectureDateTime = \Carbon\Carbon::parse($this->nextLecture['lecture_date'])
            ->setTime(
                (int) \Carbon\Carbon::parse($this->nextLecture['start_time'])->format('H'),
                (int) \Carbon\Carbon::parse($this->nextLecture['start_time'])->format('i'),
            );

        $diff = now()->diff($lectureDateTime);

        if ($diff->days > 0) {
            $this->nextLectureCountdown = "{$diff->days}d {$diff->h}h";
        } elseif ($diff->h > 0) {
            $this->nextLectureCountdown = "{$diff->h}h {$diff->i}m";
        } else {
            $this->nextLectureCountdown = "{$diff->i}m";
        }
    }

    public function getStatusLabel(string $status): string
    {
        return match($status) {
            'upcoming' => 'Upcoming',
            'today' => 'Today',
            'ongoing' => 'Ongoing',
            'completed' => 'Completed',
            default => $status,
        };
    }

    public function getStatusColor(string $status): string
    {
        return match($status) {
            'upcoming' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400',
            'today' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
            'ongoing' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
            'completed' => 'bg-zinc-100 text-zinc-600 dark:bg-zinc-500/20 dark:text-zinc-400',
            default => 'bg-zinc-100 text-zinc-600',
        };
    }

};
?>

<div class="flex h-full w-full flex-1 flex-col gap-6 p-4 lg:p-6">

    {{-- Welcome Banner --}}
    <div class="rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 p-6 text-white">
        <h1 class="text-2xl font-bold">Welcome back, {{ auth()->user()->name }}!</h1>
        <p class="mt-1 text-indigo-100">Here's what's happening with your lectures today.</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card title="Total Lectures" :value="$totalLectures" icon="academic-cap" color="indigo" />
        <x-stat-card title="Today's Lectures" :value="$todayLectures" icon="calendar-date-range" color="amber" />
        <x-stat-card title="Upcoming" :value="$upcomingLectures" icon="clock" color="green" />
        <x-stat-card title="Completed" :value="$completedLectures" icon="check-circle" color="red" />
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Next Lecture Card --}}
        <div class="lg:col-span-2">
            <h2 class="mb-3 text-lg font-semibold text-zinc-900 dark:text-white">Next Lecture</h2>
            @if($nextLecture)
                @php
                    $lectureStatus = $nextLecture['status'] ?? 'upcoming';
                @endphp
                <div class="rounded-xl border border-indigo-200 bg-gradient-to-br from-indigo-50 to-purple-50 p-6 dark:border-indigo-500/30 dark:from-indigo-500/10 dark:to-purple-500/10">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="inline-flex items-center rounded-lg bg-indigo-100 px-3 py-1 text-sm font-bold text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-400">
                                {{ $nextLecture['course_code'] }}
                            </span>
                            <h3 class="mt-3 text-xl font-bold text-zinc-900 dark:text-white">
                                {{ $nextLecture['course_title'] }}
                            </h3>
                            <div class="mt-3 space-y-2 text-sm text-zinc-600 dark:text-zinc-300">
                                <div class="flex items-center gap-2">
                                    <flux:icon.calendar-date-range class="size-4 text-indigo-500" />
                                    {{ \Carbon\Carbon::parse($nextLecture['lecture_date'])->format('l, M j, Y') }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <flux:icon.clock class="size-4 text-indigo-500" />
                                    {{ \Carbon\Carbon::parse($nextLecture['start_time'])->format('g:i A') }}{{ $nextLecture['end_time'] ? ' - '.\Carbon\Carbon::parse($nextLecture['end_time'])->format('g:i A') : '' }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <flux:icon.map-pin class="size-4 text-indigo-500" />
                                    {{ $nextLecture['venue'] }}
                                </div>
                            </div>
                        </div>

                        @if($nextLectureCountdown)
                            <div class="text-right">
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">Starts in</p>
                                <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $nextLectureCountdown }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="rounded-xl border border-zinc-200 bg-white p-8 text-center dark:border-zinc-700 dark:bg-zinc-900">
                    <flux:icon.calendar-date-range class="mx-auto size-12 text-zinc-300 dark:text-zinc-600" />
                    <p class="mt-3 text-sm text-zinc-500 dark:text-zinc-400">No upcoming lectures</p>
                    <flux:button variant="primary" href="{{ route('lectures.create') }}" class="mt-4" icon="plus" wire:navigate>
                        Add Your First Lecture
                    </flux:button>
                </div>
            @endif
        </div>

        {{-- Recent Notifications --}}
        <div>
            <h2 class="mb-3 text-lg font-semibold text-zinc-900 dark:text-white">Recent Notifications</h2>
            <div class="rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
                @forelse($recentNotifications as $notification)
                    <div class="border-b border-zinc-100 px-4 py-3 last:border-0 dark:border-zinc-700/50">
                        <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $notification['title'] }}</p>
                        <p class="mt-0.5 text-xs text-zinc-500 dark:text-zinc-400 line-clamp-2">{{ $notification['message'] }}</p>
                        <p class="mt-1 text-[11px] text-zinc-400 dark:text-zinc-500">{{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}</p>
                    </div>
                @empty
                    <div class="px-4 py-8 text-center">
                        <flux:icon.bell class="mx-auto size-8 text-zinc-300 dark:text-zinc-600" />
                        <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">No notifications yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Upcoming Lectures List --}}
    @if(count($upcomingList) > 0)
        <div>
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Upcoming Lectures</h2>
                <flux:button variant="ghost" href="{{ route('lectures.index') }}" size="sm" wire:navigate>
                    View All
                </flux:button>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($upcomingList as $lecture)
                    @php
                        $status = \App\Models\Lecture::find($lecture['id'])?->status ?? 'upcoming';
                    @endphp
                    <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center rounded-md bg-indigo-100 px-2 py-0.5 text-xs font-bold text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-400">
                                {{ $lecture['course_code'] }}
                            </span>
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-medium {{ $this->getStatusColor($status) }}">
                                {{ $this->getStatusLabel($status) }}
                            </span>
                        </div>
                        <h4 class="mt-2 font-semibold text-zinc-900 dark:text-white">{{ $lecture['course_title'] }}</h4>
                        <div class="mt-2 flex items-center gap-3 text-xs text-zinc-500 dark:text-zinc-400">
                            <span class="flex items-center gap-1">
                                <flux:icon.clock class="size-3" />
                                {{ \Carbon\Carbon::parse($lecture['start_time'])->format('g:i A') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <flux:icon.map-pin class="size-3" />
                                {{ $lecture['venue'] }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Quick Actions --}}
    <div class="flex gap-3">
        <flux:button variant="primary" href="{{ route('lectures.create') }}" icon="plus" wire:navigate>
            Add Lecture
        </flux:button>
        <flux:button variant="outline" href="{{ route('lectures.index') }}" icon="calendar-date-range" wire:navigate>
            View All Lectures
        </flux:button>
    </div>
</div>

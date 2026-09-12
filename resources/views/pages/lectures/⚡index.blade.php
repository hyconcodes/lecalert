<?php

use App\Models\Lecture;
use Flux\Flux;
use Livewire\Component;

new class extends Component
{
    public $search = '';
    public $filterStatus = 'all';
    public $lectures = [];
    public $showDeleteModal = false;
    public $deleteLectureId = null;

    public function mount(): void
    {
        $this->loadLectures();
    }

    public function updatedSearch(): void
    {
        $this->loadLectures();
    }

    public function updatedFilterStatus(): void
    {
        $this->loadLectures();
    }

    public function loadLectures(): void
    {
        $query = Lecture::where('user_id', auth()->id());

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('course_title', 'like', "%{$this->search}%")
                    ->orWhere('course_code', 'like', "%{$this->search}%")
                    ->orWhere('venue', 'like', "%{$this->search}%");
            });
        }

        $now = now();

        match($this->filterStatus) {
            'today' => $query->whereDate('lecture_date', $now->toDateString()),
            'upcoming' => $query->where(function ($q) use ($now) {
                $q->whereDate('lecture_date', '>', $now->toDateString())
                    ->orWhere(function ($q2) use ($now) {
                        $q2->whereDate('lecture_date', '=', $now->toDateString())
                            ->where('start_time', '>', $now->format('H:i:s'));
                    });
            }),
            'completed' => $query->where(function ($q) use ($now) {
                $q->whereDate('lecture_date', '<', $now->toDateString())
                    ->orWhere(function ($q2) use ($now) {
                        $q2->whereDate('lecture_date', '=', $now->toDateString())
                            ->where('end_time', '<', $now->format('H:i:s'));
                    });
            }),
            default => null,
        };

        $this->lectures = $query->orderBy('lecture_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get()
            ->toArray();
    }

    public function confirmDelete(int $lectureId): void
    {
        $this->deleteLectureId = $lectureId;
        $this->showDeleteModal = true;
    }

    public function deleteLecture(): void
    {
        if ($this->deleteLectureId) {
            Lecture::where('id', $this->deleteLectureId)
                ->where('user_id', auth()->id())
                ->delete();

            $this->showDeleteModal = false;
            $this->deleteLectureId = null;
            $this->loadLectures();
            Flux::toast(heading: 'Deleted.', text: 'Lecture deleted successfully.', variant: 'success');
        }
    }

    public function getStatus(Lecture $lecture): string
    {
        return $lecture->status;
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

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">My Lectures</h1>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Manage your lecture schedules</p>
            </div>
            <flux:button variant="primary" href="{{ route('lectures.create') }}" icon="plus" wire:navigate>
                Add Lecture
            </flux:button>
        </div>

        {{-- Filters --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="flex-1">
                <flux:input
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search lectures..."
                    icon="magnifying-glass"
                />
            </div>
            <div class="flex gap-2">
                @foreach(['all' => 'All', 'today' => 'Today', 'upcoming' => 'Upcoming', 'completed' => 'Completed'] as $value => $label)
                    <button
                        wire:click="$set('filterStatus', '{{ $value }}')"
                        class="inline-flex items-center rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ $filterStatus === $value ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-400' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200 dark:bg-zinc-800 dark:text-zinc-400 dark:hover:bg-zinc-700' }}"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Lectures List --}}
        @if(count($lectures) > 0)
            <div class="grid gap-4">
                @foreach($lectures as $lectureData)
                    @php
                        $lecture = \App\Models\Lecture::find($lectureData['id']);
                        $status = $lecture?->status ?? 'upcoming';
                    @endphp
                    <div class="group relative rounded-xl border border-zinc-200 bg-white p-5 transition-all hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900 dark:hover:border-zinc-600">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center rounded-lg bg-indigo-100 px-3 py-1 text-sm font-bold text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-400">
                                        {{ $lectureData['course_code'] }}
                                    </span>
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $this->getStatusColor($status) }}">
                                        {{ $this->getStatusLabel($status) }}
                                    </span>
                                </div>

                                <h3 class="mt-2 text-lg font-semibold text-zinc-900 dark:text-white">
                                    {{ $lectureData['course_title'] }}
                                </h3>

                                <div class="mt-3 flex flex-wrap gap-4 text-sm text-zinc-500 dark:text-zinc-400">
                                    <span class="flex items-center gap-1.5">
                                        <flux:icon.calendar-date-range class="size-4" />
                                        {{ \Carbon\Carbon::parse($lectureData['lecture_date'])->format('l, M j, Y') }}
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <flux:icon.clock class="size-4" />
                                        {{ \Carbon\Carbon::parse($lectureData['start_time'])->format('g:i A') }}{{ $lectureData['end_time'] ? ' - '.\Carbon\Carbon::parse($lectureData['end_time'])->format('g:i A') : '' }}
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <flux:icon.map-pin class="size-4" />
                                        {{ $lectureData['venue'] }}
                                    </span>
                                </div>

                                <div class="mt-2 flex items-center gap-1.5 text-xs text-zinc-400 dark:text-zinc-500">
                                    <flux:icon.bell class="size-3.5" />
                                    Reminder: {{ $lectureData['reminder_minutes'] }} min before
                                </div>
                            </div>

                            <div class="flex items-center gap-1 opacity-0 transition-opacity group-hover:opacity-100">
                                <flux:button icon="pencil" variant="ghost" size="sm" href="{{ route('lectures.edit', $lectureData['id']) }}" wire:navigate>
                                    Edit
                                </flux:button>
                                <flux:button icon="trash" variant="ghost" size="sm" color="red" wire:click="confirmDelete({{ $lectureData['id'] }})">
                                    Delete
                                </flux:button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <x-empty-state
                title="No Lectures Yet"
                description="You haven't added any lecture schedules. Start by adding your first lecture."
                icon="calendar-date-range"
                actionLabel="Add Your First Lecture"
                actionUrl="{{ route('lectures.create') }}"
            />
        @endif

        {{-- Delete Confirmation Modal --}}
        <flux:modal wire:model="showDeleteModal">
            <div class="text-center">
                <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-red-100 dark:bg-red-500/20">
                    <flux:icon.trash class="size-6 text-red-600 dark:text-red-400" />
                </div>
                <h3 class="mt-4 text-lg font-semibold text-zinc-900 dark:text-white">Delete Lecture</h3>
                <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                    Are you sure you want to delete this lecture? This action cannot be undone.
                </p>
                <div class="mt-6 flex justify-center gap-3">
                    <flux:button wire:click="$set('showDeleteModal', false)" variant="outline">
                        Cancel
                    </flux:button>
                    <flux:button wire:click="deleteLecture" variant="primary" color="red">
                        Delete
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    </div>
</div>

<?php

use App\Models\Lecture;
use Flux\Flux;
use Livewire\Component;

new class extends Component
{
    public $lectureId;
    public $courseTitle = '';
    public $courseCode = '';
    public $lectureDate = '';
    public $startTime = '';
    public $endTime = '';
    public $venue = '';
    public $reminderMinutes = 30;

    protected array $rules = [
        'courseTitle' => 'required|string|max:255',
        'courseCode' => 'required|string|max:50',
        'lectureDate' => 'required|date',
        'startTime' => 'required',
        'endTime' => 'nullable',
        'venue' => 'required|string|max:255',
        'reminderMinutes' => 'required|integer|min:1|max:120',
    ];

    protected array $validationAttributes = [
        'courseTitle' => 'course title',
        'courseCode' => 'course code',
        'lectureDate' => 'lecture date',
        'startTime' => 'start time',
        'endTime' => 'end time',
        'venue' => 'venue',
        'reminderMinutes' => 'reminder time',
    ];

    public function mount(int $lecture): void
    {
        $this->lectureId = $lecture;

        $lectureModel = Lecture::where('id', $lecture)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $this->courseTitle = $lectureModel->course_title;
        $this->courseCode = $lectureModel->course_code;
        $this->lectureDate = $lectureModel->lecture_date->format('Y-m-d');
        $this->startTime = $lectureModel->start_time->format('H:i');
        $this->endTime = $lectureModel->end_time?->format('H:i') ?? '';
        $this->venue = $lectureModel->venue;
        $this->reminderMinutes = $lectureModel->reminder_minutes;
    }

    public function updated($field): void
    {
        $this->validateOnly($field);
    }

    public function save(): void
    {
        $this->validate();

        Lecture::where('id', $this->lectureId)
            ->where('user_id', auth()->id())
            ->update([
                'course_title' => $this->courseTitle,
                'course_code' => $this->courseCode,
                'lecture_date' => $this->lectureDate,
                'start_time' => $this->startTime,
                'end_time' => $this->endTime ?: null,
                'venue' => $this->venue,
                'reminder_minutes' => $this->reminderMinutes,
            ]);

        Flux::toast(heading: 'Lecture updated.', variant: 'success');
        $this->redirectRoute('lectures.index');
    }

};
?>

<div class="flex h-full w-full flex-1 flex-col gap-6 p-4 lg:p-6">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Edit Lecture</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Update the lecture schedule details</p>
        </div>

        <form wire:submit="save" class="max-w-2xl space-y-6">
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">Course Information</h2>

                <div class="grid gap-4 sm:grid-cols-2">
                    <flux:field>
                        <flux:label>Course Title</flux:label>
                        <flux:input wire:model="courseTitle" placeholder="e.g. Artificial Intelligence" />
                        <flux:error name="courseTitle" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Course Code</flux:label>
                        <flux:input wire:model="courseCode" placeholder="e.g. CSC 401" />
                        <flux:error name="courseCode" />
                    </flux:field>
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">Schedule</h2>

                <div class="grid gap-4 sm:grid-cols-2">
                    <flux:field>
                        <flux:label>Lecture Date</flux:label>
                        <flux:input wire:model="lectureDate" type="date" />
                        <flux:error name="lectureDate" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Venue</flux:label>
                        <flux:input wire:model="venue" placeholder="e.g. Computer Science Lab" />
                        <flux:error name="venue" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Start Time</flux:label>
                        <flux:input wire:model="startTime" type="time" />
                        <flux:error name="startTime" />
                    </flux:field>

                    <flux:field>
                        <flux:label>End Time (Optional)</flux:label>
                        <flux:input wire:model="endTime" type="time" />
                        <flux:error name="endTime" />
                    </flux:field>
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">Reminder</h2>

                <flux:field>
                    <flux:label>Remind me before the lecture</flux:label>
                    <flux:select wire:model="reminderMinutes">
                        <flux:select.option value="10">10 minutes before</flux:select.option>
                        <flux:select.option value="15">15 minutes before</flux:select.option>
                        <flux:select.option value="30">30 minutes before</flux:select.option>
                        <flux:select.option value="60">1 hour before</flux:select.option>
                        <flux:select.option value="120">2 hours before</flux:select.option>
                    </flux:select>
                    <flux:error name="reminderMinutes" />
                </flux:field>
            </div>

            <div class="flex justify-end gap-3">
                <flux:button variant="outline" href="{{ route('lectures.index') }}" wire:navigate>
                    Cancel
                </flux:button>
                <flux:button variant="primary" type="submit" icon="check">
                    Update Lecture
                </flux:button>
            </div>
        </form>
    </div>

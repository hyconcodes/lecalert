<?php

use App\Models\Lecture;
use Livewire\Component;

new class extends Component
{
    public $courseTitle = '';
    public $courseCode = '';
    public $lectureDate = '';
    public $startTime = '';
    public $endTime = '';
    public $venue = '';
    public $reminderMinutes = 30;
    public $showSuccess = false;

    protected array $rules = [
        'courseTitle' => 'required|string|max:255',
        'courseCode' => 'required|string|max:50',
        'lectureDate' => 'required|date|after_or_equal:today',
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

    public function updated($field): void
    {
        $this->validateOnly($field);
    }

    public function save(): void
    {
        $this->validate();

        Lecture::create([
            'user_id' => auth()->id(),
            'course_title' => $this->courseTitle,
            'course_code' => $this->courseCode,
            'lecture_date' => $this->lectureDate,
            'start_time' => $this->startTime,
            'end_time' => $this->endTime ?: null,
            'venue' => $this->venue,
            'reminder_minutes' => $this->reminderMinutes,
        ]);

        session()->flash('success', 'Lecture added successfully!');
        $this->redirectRoute('lectures.index');
    }

};
?>

<div class="flex h-full w-full flex-1 flex-col gap-6 p-4 lg:p-6">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Add New Lecture</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Fill in the details to add a new lecture schedule</p>
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
                    Save Lecture
                </flux:button>
            </div>
        </form>
    </div>
</div>

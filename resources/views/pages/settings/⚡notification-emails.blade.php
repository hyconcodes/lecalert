<?php

use App\Mail\OtpVerificationMail;
use App\Models\NotificationEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;

new class extends Component
{
    public $emails = [];
    public $newEmail = '';
    public $verificationCode = '';
    public $verifyingEmailId = null;
    public $showVerifyModal = false;
    public $showAddForm = false;

    protected array $rules = [
        'newEmail' => 'required|email|max:255',
    ];

    public function mount(): void
    {
        $this->loadEmails();
    }

    public function loadEmails(): void
    {
        $this->emails = NotificationEmail::where('user_id', auth()->id())->get()->toArray();
    }

    public function addEmail(): void
    {
        if (count($this->emails) >= 2) {
            session()->flash('error', 'You can only add up to 2 notification email addresses.');
            return;
        }

        $this->validate();

        $existing = NotificationEmail::where('user_id', auth()->id())
            ->where('email', $this->newEmail)
            ->exists();

        if ($existing) {
            session()->flash('error', 'This email address is already added.');
            return;
        }

        $otpCode = strtoupper(Str::random(6));

        $emailRecord = NotificationEmail::create([
            'user_id' => auth()->id(),
            'email' => $this->newEmail,
            'verification_code' => $otpCode,
        ]);

        Mail::to($this->newEmail)->send(new OtpVerificationMail(auth()->user()->name, $otpCode));

        $this->verifyingEmailId = $emailRecord->id;
        $this->showVerifyModal = true;
        $this->showAddForm = false;
        $this->newEmail = '';

        session()->flash('success', 'Verification code sent to your email.');
    }

    public function verifyEmail(): void
    {
        $emailRecord = NotificationEmail::where('id', $this->verifyingEmailId)
            ->where('user_id', auth()->id())
            ->where('verification_code', $this->verificationCode)
            ->first();

        if (! $emailRecord) {
            session()->flash('error', 'Invalid verification code.');
            return;
        }

        $emailRecord->update([
            'is_verified' => true,
            'verified_at' => now(),
            'verification_code' => null,
        ]);

        $this->showVerifyModal = false;
        $this->verificationCode = '';
        $this->verifyingEmailId = null;

        $this->loadEmails();
        session()->flash('success', 'Email verified successfully!');
    }

    public function removeEmail(int $emailId): void
    {
        NotificationEmail::where('id', $emailId)
            ->where('user_id', auth()->id())
            ->delete();

        $this->loadEmails();
        session()->flash('success', 'Email removed successfully.');
    }

};
?>

<div class="flex h-full w-full flex-1 flex-col gap-6 p-4 lg:p-6">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Notification Emails</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Add extra email addresses to receive lecture reminders (max 2)</p>
        </div>

        @if(session('success'))
            <div class="rounded-lg bg-emerald-50 p-4 text-sm text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700 dark:bg-red-500/10 dark:text-red-400">
                {{ session('error') }}
            </div>
        @endif

        <div class="max-w-2xl space-y-6">
            {{-- Current Emails --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">Added Email Addresses</h2>

                @forelse($emails as $email)
                    <div class="flex items-center justify-between rounded-lg border border-zinc-200 p-4 dark:border-zinc-700 {{ $email['is_verified'] ? '' : 'border-dashed' }}">
                        <div class="flex items-center gap-3">
                            <div class="flex size-10 items-center justify-center rounded-full {{ $email['is_verified'] ? 'bg-emerald-100 dark:bg-emerald-500/20' : 'bg-amber-100 dark:bg-amber-500/20' }}">
                                <flux:icon.envelope class="size-5 {{ $email['is_verified'] ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}" />
                            </div>
                            <div>
                                <p class="font-medium text-zinc-900 dark:text-white">{{ $email['email'] }}</p>
                                <p class="text-xs {{ $email['is_verified'] ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                    {{ $email['is_verified'] ? 'Verified' : 'Not verified' }}
                                </p>
                            </div>
                        </div>
                        <flux:button icon="trash" variant="ghost" size="sm" color="red" wire:click="removeEmail({{ $email['id'] }})">
                            Remove
                        </flux:button>
                    </div>
                @empty
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">No additional email addresses added yet.</p>
                @endforelse

                @if(count($emails) < 2)
                    <div class="mt-4">
                        @if($showAddForm)
                            <form wire:submit="addEmail" class="flex gap-3">
                                <flux:input wire:model="newEmail" type="email" placeholder="Enter email address" class="flex-1" />
                                <flux:button variant="primary" type="submit" icon="plus">
                                    Add
                                </flux:button>
                                <flux:button variant="ghost" wire:click="$set('showAddForm', false)">
                                    Cancel
                                </flux:button>
                            </form>
                        @else
                            <flux:button variant="outline" wire:click="$set('showAddForm', true)" icon="plus">
                                Add Email Address
                            </flux:button>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex justify-start">
                <flux:button variant="ghost" href="{{ route('settings.edit') }}" icon="arrow-left" wire:navigate>
                    Back to Settings
                </flux:button>
            </div>
        </div>

        {{-- Verification Modal --}}
        <flux:modal wire:model="showVerifyModal">
            <div class="text-center">
                <div class="mx-auto flex size-12 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-500/20">
                    <flux:icon.envelope class="size-6 text-indigo-600 dark:text-indigo-400" />
                </div>
                <h3 class="mt-4 text-lg font-semibold text-zinc-900 dark:text-white">Verify Email</h3>
                <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                    Enter the 6-digit code sent to your email to verify it.
                </p>
                <div class="mt-4">
                    <flux:input wire:model="verificationCode" placeholder="Enter 6-digit code" maxlength="6" class="text-center text-lg tracking-[0.5em]" />
                </div>
                <div class="mt-6 flex justify-center gap-3">
                    <flux:button wire:click="$set('showVerifyModal', false)" variant="outline">
                        Cancel
                    </flux:button>
                    <flux:button wire:click="verifyEmail" variant="primary" icon="check">
                        Verify
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    </div>
</div>

<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

new class extends Component
{
    public $name = '';
    public $email = '';
    public $currentPassword = '';
    public $newPassword = '';
    public $newPasswordConfirmation = '';

    public function mount(): void
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateProfile(): void
    {
        $user = auth()->user();

        Validator::make([
            'name' => $this->name,
            'email' => $this->email,
        ], [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'regex:/^[a-zA-Z]+\.[0-9]+@bouesti\.edu\.ng$/',
                \Illuminate\Validation\Rule::unique(User::class)->ignore($user->id),
            ],
        ])->validate();

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        session()->flash('success', 'Profile updated successfully.');
    }

    public function changePassword(): void
    {
        $user = auth()->user();

        Validator::make([
            'current_password' => $this->currentPassword,
            'password' => $this->newPassword,
            'password_confirmation' => $this->newPasswordConfirmation,
        ], [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ])->validate();

        if (! Hash::check($this->currentPassword, $user->password)) {
            session()->flash('error', 'Current password is incorrect.');
            return;
        }

        $user->update([
            'password' => $this->newPassword,
        ]);

        $this->currentPassword = '';
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';

        session()->flash('success', 'Password changed successfully.');
    }

};
?>

<div class="flex h-full w-full flex-1 flex-col gap-6 p-4 lg:p-6">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Profile</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Manage your account information</p>
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
            {{-- Profile Information --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">Profile Information</h2>

                <div class="mb-6 flex items-center gap-4">
                    <div class="flex size-16 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-500/20">
                        <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400">{{ auth()->user()->initials() }}</span>
                    </div>
                    <div>
                        <p class="font-semibold text-zinc-900 dark:text-white">{{ auth()->user()->name }}</p>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ auth()->user()->email }}</p>
                        <p class="text-xs text-zinc-400 dark:text-zinc-500">Member since {{ auth()->user()->created_at->format('M j, Y') }}</p>
                    </div>
                </div>

                <form wire:submit="updateProfile" class="space-y-4">
                    <flux:field>
                        <flux:label>Full Name</flux:label>
                        <flux:input wire:model="name" />
                        <flux:error name="name" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Email Address</flux:label>
                        <flux:input wire:model="email" type="email" placeholder="lastname.matricno@bouesti.edu.ng" />
                        <flux:error name="email" />
                        <flux:text class="text-xs">Only institution emails (lastname.matricno@bouesti.edu.ng) are allowed.</flux:text>
                    </flux:field>

                    <div class="flex justify-end">
                        <flux:button variant="primary" type="submit" icon="check">
                            Save Changes
                        </flux:button>
                    </div>
                </form>
            </div>

            {{-- Change Password --}}
            <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-white">Change Password</h2>

                <form wire:submit="changePassword" class="space-y-4">
                    <flux:field>
                        <flux:label>Current Password</flux:label>
                        <flux:input wire:model="currentPassword" type="password" />
                        <flux:error name="current_password" />
                    </flux:field>

                    <flux:field>
                        <flux:label>New Password</flux:label>
                        <flux:input wire:model="newPassword" type="password" />
                        <flux:error name="password" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Confirm New Password</flux:label>
                        <flux:input wire:model="newPasswordConfirmation" type="password" />
                    </flux:field>

                    <div class="flex justify-end">
                        <flux:button variant="primary" type="submit" icon="key">
                            Change Password
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

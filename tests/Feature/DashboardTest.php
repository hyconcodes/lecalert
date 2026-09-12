<?php

use App\Models\Lecture;
use App\Models\User;

it('can display the dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Dashboard');
});

it('shows correct stats on dashboard', function () {
    $user = User::factory()->create();
    Lecture::factory()->count(3)->create(['user_id' => $user->id]);
    Lecture::factory()->today()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Total Lectures')
        ->assertSee('Welcome back');
});

it('shows upcoming lectures on dashboard', function () {
    $user = User::factory()->create();
    Lecture::factory()->upcoming()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Upcoming Lectures');
});

it('shows empty state when no lectures exist', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('No upcoming lectures');
});

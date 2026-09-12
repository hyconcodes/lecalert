<?php

use App\Models\Notification;
use App\Models\User;

it('can display the notifications page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('notifications.index'))
        ->assertOk()
        ->assertSee('Notifications');
});

it('can display notifications list', function () {
    $user = User::factory()->create();
    Notification::factory()->count(3)->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('notifications.index'))
        ->assertOk();
});

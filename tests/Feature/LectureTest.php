<?php

use App\Models\Lecture;
use App\Models\User;

it('can display the lectures index page', function () {
    $user = User::factory()->create();
    Lecture::factory()->count(3)->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('lectures.index'))
        ->assertOk()
        ->assertSee('My Lectures');
});

it('can display the create lecture form', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('lectures.create'))
        ->assertOk()
        ->assertSee('Add New Lecture');
});

it('can display the edit lecture form', function () {
    $user = User::factory()->create();
    $lecture = Lecture::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('lectures.edit', $lecture))
        ->assertOk()
        ->assertSee('Edit Lecture');
});

it('can create a lecture in database', function () {
    $user = User::factory()->create();
    $lecture = Lecture::factory()->create(['user_id' => $user->id]);

    $this->assertDatabaseHas('lectures', [
        'user_id' => $user->id,
        'id' => $lecture->id,
    ]);
});

it('can delete a lecture from database', function () {
    $user = User::factory()->create();
    $lecture = Lecture::factory()->create(['user_id' => $user->id]);

    $lecture->delete();

    $this->assertDatabaseMissing('lectures', ['id' => $lecture->id]);
});

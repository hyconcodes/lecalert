<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('dashboard', 'pages::dashboard')->name('dashboard');

    // Lectures
    Route::prefix('lectures')->name('lectures.')->group(function () {
        Route::livewire('/', 'pages::lectures.index')->name('index');
        Route::livewire('/create', 'pages::lectures.create')->name('create');
        Route::livewire('/{lecture}/edit', 'pages::lectures.edit')->name('edit');
    });

    // Notifications
    Route::livewire('/notifications', 'pages::notifications.index')->name('notifications.index');

    // Custom Settings (LecAlert-specific)
    Route::get('/lecalert-settings', fn () => view('pages.settings.index'))->name('lecalert.settings');
    Route::put('/lecalert-settings', function () {
        return back()->with('success', 'Settings updated successfully.');
    })->name('lecalert.settings.update');

    // Notification Emails
    Route::livewire('/settings/notification-emails', 'pages::settings.notification-emails')->name('notification-emails');
});

require __DIR__.'/settings.php';

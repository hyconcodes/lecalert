<?php

use Illuminate\Foundation\Inspiration;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiration::quote());
})->purpose('Display an inspiring quote');

Schedule::command('reminders:check')->everyMinute();

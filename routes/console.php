<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote');


Schedule::command('app:send-email-sequence')->everyMinute();
// Schedule::command('app:send-email-sequence')->everyFiveMinutes();

// Schedule::command('app:send-email-sequence')
//     ->everyFiveMinutes()
//     ->withoutOverlapping()
//     ->runInBackground();

<?php

use App\Jobs\ExpireBookingJob;
use App\Jobs\ExpirePaymentJob;
use App\Jobs\ProcessQueueJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Run every minute: expire overdue bookings and payments, process queue
Schedule::job(new ExpireBookingJob)->everyMinute();
Schedule::job(new ExpirePaymentJob)->everyMinute();
Schedule::job(new ProcessQueueJob)->everyMinute();

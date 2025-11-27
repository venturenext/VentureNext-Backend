<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Queue worker yang dijalankan setiap menit
Schedule::command('queue:work', [
    'database',
    '--queue=default,emails',
    '--stop-when-empty',
    '--max-time=50'
])->everyMinute()->withoutOverlapping();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Bersihkan file yatim storage setiap minggu (Senin 02:30).
// Hosting perlu cron: * * * * * php /path/artisan schedule:run >> /dev/null 2>&1
Schedule::command('storage:cleanup-orphans --force')->weeklyOn(1, '02:30');

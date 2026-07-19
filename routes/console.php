<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Daily checks
Schedule::command('opportunities:check-deadlines')->dailyAt('08:00');
Schedule::command('schemes:check-deadlines')->dailyAt('08:30');

// Daily — RSS scrape for new jobs
Schedule::command('opportunities:scrape-rss')->dailyAt('07:00');

// Weekly
Schedule::command('schemes:fetch-myscheme --limit=50')->weekly()->mondays()->at('09:00');
Schedule::command('opportunities:ai-discover --count=10')->weekly()->mondays()->at('09:30');
Schedule::command('opportunities:scrape-web')->weekly()->wednesdays()->at('10:00');

// Monthly
Schedule::command('schemes:ai-discover')->monthly()->at('10:00');
Schedule::command('opportunities:ai-discover --count=15')->monthly()->at('11:00');

// Every 15 minutes
Schedule::command('portals:check-status')->everyFifteenMinutes();
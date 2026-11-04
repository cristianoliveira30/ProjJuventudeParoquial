<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Log de acesso guarda IP e navegador (dado pessoal); mantém só 90 dias.
Schedule::call(fn () => DB::table('page_access_logs')->where('created_at', '<', now()->subDays(90))->delete())
    ->daily()
    ->name('prune-page-access-logs');

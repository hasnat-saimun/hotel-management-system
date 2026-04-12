<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\RoomBlock;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('room-blocks:release-expired', function () {
    $now = now();

    $count = RoomBlock::query()
        ->where('status', '!=', 'cancelled')
        ->whereNull('released_at')
        ->whereNotNull('release_at')
        ->where('release_at', '<=', $now)
        ->update(['released_at' => $now]);

    $this->info("Released {$count} expired room block(s).");
})->purpose('Auto-release room blocks past their release deadline');

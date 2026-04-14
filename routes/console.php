<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\RoomBlock;
use App\Models\RoomBlockRoom;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('room-blocks:release-expired', function () {
    $now = now();

    $count = 0;

    DB::transaction(function () use ($now, &$count) {
        $ids = RoomBlock::query()
            ->where('status', '!=', 'cancelled')
            ->whereNull('released_at')
            ->whereNotNull('release_at')
            ->where('release_at', '<=', $now)
            ->pluck('id');

        $count = (int) $ids->count();
        if ($count < 1) {
            return;
        }

        RoomBlock::query()
            ->whereIn('id', $ids)
            ->update(['released_at' => $now]);

        RoomBlockRoom::query()
            ->whereIn('room_block_id', $ids)
            ->where('status', 'blocked')
            ->update([
                'status' => 'released',
                'reservation_id' => null,
            ]);
    });

    $this->info("Released {$count} expired room block(s).");
})->purpose('Auto-release room blocks past their release deadline');

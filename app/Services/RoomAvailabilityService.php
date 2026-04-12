<?php

namespace App\Services;

use App\Models\Room;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class RoomAvailabilityService
{
    /**
     * Constrains a Room query to only rooms that are available for the given date range.
     *
     * Notes:
     * - End date is treated as exclusive (same as existing reservation overlap logic).
     * - Blocks are excluded unless $ignoreBlocks = true or they belong to $ignoreRoomBlockId.
     */
    public function constrainToAvailableRooms(
        Builder $roomQuery,
        string $fromDate,
        string $toDate,
        bool $ignoreBlocks = false,
        ?int $ignoreRoomBlockId = null
    ): Builder {
        $from = Carbon::parse($fromDate)->toDateString();
        $to = Carbon::parse($toDate)->toDateString();

        $roomQuery
            ->where('is_active', true)
            ->where('status', 'available')
            ->whereDoesntHave('reservations', function ($reservationQuery) use ($from, $to) {
                $reservationQuery
                    ->whereIn('reservations.status', ['pending', 'confirmed', 'checked_in', 'booked'])
                    ->where('reservations.check_in_date', '<', $to)
                    ->where('reservations.check_out_date', '>', $from);
            });

        if ($ignoreBlocks) {
            return $roomQuery;
        }

        $roomQuery->whereDoesntHave('roomBlocks', function ($blockQuery) use ($from, $to, $ignoreRoomBlockId) {
            $blockQuery
                ->active()
                ->where('room_blocks.start_date', '<', $to)
                ->where('room_blocks.end_date', '>', $from)
                ->where('room_block_rooms.status', 'blocked')
                ->when($ignoreRoomBlockId, function ($q) use ($ignoreRoomBlockId) {
                    $q->where('room_blocks.id', '!=', $ignoreRoomBlockId);
                });
        });

        return $roomQuery;
    }

    public function roomIdsAvailableForRange(
        string $fromDate,
        string $toDate,
        ?int $roomTypeId = null,
        bool $ignoreBlocks = false,
        ?int $ignoreRoomBlockId = null
    ) {
        $query = Room::query()->select('rooms.id');

        if ($roomTypeId) {
            $query->where('room_type_id', $roomTypeId);
        }

        $this->constrainToAvailableRooms($query, $fromDate, $toDate, $ignoreBlocks, $ignoreRoomBlockId);

        return $query->orderBy('room_number')->pluck('id');
    }
}

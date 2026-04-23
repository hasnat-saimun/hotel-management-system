<?php

namespace App\Services;

use App\Models\HousekeepingTask;
use App\Models\Reservation;
use App\Models\ReservationRoom;
use App\Models\Room;
use App\Models\Stay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomRackActionService
{
    public function rackDateFromRequest(Request $request): Carbon
    {
        if (!$request->filled('rack_date')) {
            return Carbon::today()->startOfDay();
        }

        return Carbon::parse((string) $request->input('rack_date'))->startOfDay();
    }

    public function roomDetails(Room $room, Carbon $rackDate): array
    {
        $loadedRoom = Room::query()
            ->select(['id', 'room_number', 'room_type_id', 'floor_id', 'status'])
            ->with([
                'roomType:id,name',
                'floor:id,name,level_number',
            ])
            ->whereKey($room->id)
            ->firstOrFail();

        $activeStay = Stay::query()
            ->with([
                'reservation:id,guest_id,reservation_code,check_in_date,check_out_date,status,channel,adults,children',
                'reservation.guest:id,first_name,last_name,phone,vip',
            ])
            ->where('room_id', $loadedRoom->id)
            ->where('status', 'in_house')
            ->whereNull('check_out_time')
            ->orderByDesc('check_in_time')
            ->first();

        $arrivalReservation = Reservation::query()
            ->with([
                'guest:id,first_name,last_name,phone,vip',
                'reservationRooms:id,reservation_id,room_id,status',
            ])
            ->whereIn('status', ['booked', 'confirmed'])
            ->whereDate('check_in_date', $rackDate)
            ->whereHas('reservationRooms', function ($query) use ($loadedRoom) {
                $query
                    ->where('room_id', $loadedRoom->id)
                    ->where('status', 'reserved');
            })
            ->orderBy('id')
            ->first();

        $guest = $activeStay?->reservation?->guest ?: $arrivalReservation?->guest;

        $openHousekeepingTask = HousekeepingTask::query()
            ->where('room_id', $loadedRoom->id)
            ->whereIn('status', ['pending', 'in_progress'])
            ->whereBetween('task_date', [$rackDate->copy()->startOfDay(), $rackDate->copy()->endOfDay()])
            ->orderByDesc('task_date')
            ->first();

        $roomStatus = (string) ($loadedRoom->status ?? 'available');
        $rackStatus = $this->deriveRackStatusForModal($roomStatus, $activeStay !== null, $arrivalReservation !== null, $openHousekeepingTask !== null);

        $stayDurationNights = null;
        if ($activeStay?->check_in_time) {
            $stayDurationNights = Carbon::parse($activeStay->check_in_time)->startOfDay()->diffInDays(now()->startOfDay());
        }

        return [
            'room_id' => $loadedRoom->id,
            'room_number' => (string) ($loadedRoom->room_number ?? '-'),
            'room_type' => (string) ($loadedRoom->roomType?->name ?? '-'),
            'floor_name' => (string) ($loadedRoom->floor?->name ?? '-'),
            'raw_room_status' => $roomStatus,
            'rack_status' => $rackStatus['key'],
            'rack_status_label' => $rackStatus['label'],
            'guest' => [
                'name' => $guest ? trim(($guest->first_name ?? '') . ' ' . ($guest->last_name ?? '')) : null,
                'phone' => $guest?->phone,
                'vip' => (bool) ($guest?->vip ?? false),
            ],
            'reservation' => $arrivalReservation ? [
                'id' => $arrivalReservation->id,
                'code' => $arrivalReservation->reservation_code,
                'status' => $arrivalReservation->status,
                'channel' => $arrivalReservation->channel,
                'check_in_date' => optional($arrivalReservation->check_in_date)->toDateString(),
                'check_out_date' => optional($arrivalReservation->check_out_date)->toDateString(),
                'adults' => (int) ($arrivalReservation->adults ?? 1),
                'children' => (int) ($arrivalReservation->children ?? 0),
            ] : null,
            'stay' => $activeStay ? [
                'id' => $activeStay->id,
                'status' => $activeStay->status,
                'check_in_time' => optional($activeStay->check_in_time)->toDateTimeString(),
                'check_out_time' => optional($activeStay->check_out_time)->toDateTimeString(),
                'nights' => $stayDurationNights,
                'expected_check_out_date' => optional($activeStay->reservation?->check_out_date)->toDateString(),
            ] : null,
            'housekeeping' => [
                'status' => $openHousekeepingTask?->status,
                'priority' => $openHousekeepingTask?->priority,
            ],
            'actions' => [
                'can_check_in' => $rackStatus['key'] === 'reserved' && $arrivalReservation !== null,
                'can_check_out' => $rackStatus['key'] === 'occupied' && $activeStay !== null,
                'can_mark_dirty' => !$activeStay && $roomStatus !== 'out_of_service',
                'can_mark_clean' => !$activeStay && $roomStatus !== 'out_of_service',
                'can_mark_available' => !$activeStay && $roomStatus !== 'out_of_service',
                'can_mark_maintenance' => !$activeStay && $roomStatus !== 'out_of_service',
                'can_block' => !$activeStay && $roomStatus !== 'out_of_service',
                'can_unblock' => !$activeStay && $roomStatus === 'out_of_service',
            ],
        ];
    }

    public function checkIn(Room $room, Carbon $rackDate): string
    {
        DB::transaction(function () use ($room, $rackDate) {
            $lockedRoom = Room::query()->whereKey($room->id)->lockForUpdate()->firstOrFail();

            $activeStay = Stay::query()
                ->where('room_id', $lockedRoom->id)
                ->where('status', 'in_house')
                ->whereNull('check_out_time')
                ->lockForUpdate()
                ->exists();

            if ($activeStay) {
                throw new \RuntimeException('Room already has an active in-house stay.');
            }

            $reservation = Reservation::query()
                ->whereIn('status', ['booked', 'confirmed'])
                ->whereDate('check_in_date', $rackDate)
                ->whereHas('reservationRooms', function ($query) use ($lockedRoom) {
                    $query
                        ->where('room_id', $lockedRoom->id)
                        ->where('status', 'reserved');
                })
                ->lockForUpdate()
                ->first();

            if (!$reservation) {
                throw new \RuntimeException('No reserved arrival found for this room on the selected date.');
            }

            $reservationRooms = ReservationRoom::query()
                ->where('reservation_id', $reservation->id)
                ->lockForUpdate()
                ->get();

            if ($reservationRooms->isEmpty()) {
                throw new \RuntimeException('No rooms are assigned to this reservation.');
            }

            $alreadyCheckedIn = Stay::query()
                ->where('reservation_id', $reservation->id)
                ->where('status', 'in_house')
                ->whereNull('check_out_time')
                ->lockForUpdate()
                ->exists();

            if ($alreadyCheckedIn) {
                throw new \RuntimeException('This reservation already has an active stay.');
            }

            if (($reservation->status ?? null) === 'booked') {
                $reservation->status = 'confirmed';
                $reservation->save();
            }

            $checkInTime = now();
            foreach ($reservationRooms as $reservationRoom) {
                Stay::create([
                    'reservation_id' => $reservation->id,
                    'room_id' => $reservationRoom->room_id,
                    'check_in_time' => $checkInTime,
                    'check_out_time' => null,
                    'status' => 'in_house',
                    'adults' => (int) ($reservation->adults ?? 1),
                    'children' => (int) ($reservation->children ?? 0),
                ]);
            }

            ReservationRoom::query()
                ->where('reservation_id', $reservation->id)
                ->update(['status' => 'occupied']);

            $roomIds = $reservationRooms->pluck('room_id')->filter()->values();
            if ($roomIds->isNotEmpty()) {
                Room::query()->whereIn('id', $roomIds)->update(['status' => 'occupied']);
            }
        });

        app(RoomRackService::class)->bustSnapshotCache();

        return 'Guest checked in successfully.';
    }

    public function checkOut(Room $room): string
    {
        DB::transaction(function () use ($room) {
            $lockedRoom = Room::query()->whereKey($room->id)->lockForUpdate()->firstOrFail();

            $stay = Stay::query()
                ->where('room_id', $lockedRoom->id)
                ->where('status', 'in_house')
                ->whereNull('check_out_time')
                ->lockForUpdate()
                ->first();

            if (!$stay) {
                throw new \RuntimeException('No active in-house stay found for this room.');
            }

            $stay->check_out_time = now();
            $stay->status = 'checked_out';
            $stay->save();

            ReservationRoom::query()
                ->where('reservation_id', $stay->reservation_id)
                ->where('room_id', $stay->room_id)
                ->lockForUpdate()
                ->update(['status' => 'released']);

            if (($lockedRoom->status ?? null) !== 'out_of_service') {
                $lockedRoom->status = 'dirty';
                $lockedRoom->save();
            }
        });

        app(RoomRackService::class)->bustSnapshotCache();

        return 'Guest checked out successfully. Room marked as dirty.';
    }

    public function updateHousekeeping(Room $room, string $state): string
    {
        DB::transaction(function () use ($room, $state) {
            $lockedRoom = Room::query()->whereKey($room->id)->lockForUpdate()->firstOrFail();

            $hasActiveStay = Stay::query()
                ->where('room_id', $lockedRoom->id)
                ->where('status', 'in_house')
                ->whereNull('check_out_time')
                ->lockForUpdate()
                ->exists();

            if ($hasActiveStay) {
                throw new \RuntimeException('Cannot change housekeeping state while room is occupied.');
            }

            if (($lockedRoom->status ?? null) === 'out_of_service') {
                throw new \RuntimeException('Unblock the room first before changing housekeeping state.');
            }

            if ($state === 'dirty') {
                $lockedRoom->status = 'dirty';
                $lockedRoom->save();

                return;
            }

            if ($state === 'maintenance') {
                $lockedRoom->status = 'maintenance';
                $lockedRoom->save();

                return;
            }

            if ($state === 'clean') {
                $lockedRoom->status = 'clean';
                $lockedRoom->save();

                return;
            }

            $lockedRoom->status = 'available';
            $lockedRoom->save();

            HousekeepingTask::query()
                ->where('room_id', $lockedRoom->id)
                ->whereIn('status', ['pending', 'in_progress'])
                ->update([
                    'status' => 'done',
                    'completed_at' => now(),
                ]);
        });

        app(RoomRackService::class)->bustSnapshotCache();

        if ($state === 'dirty') {
            return 'Room marked dirty.';
        }

        if ($state === 'maintenance') {
            return 'Room marked maintenance.';
        }

        if ($state === 'clean') {
            return 'Room marked clean.';
        }

        return 'Room marked available and open housekeeping tasks completed.';
    }

    public function block(Room $room): string
    {
        DB::transaction(function () use ($room) {
            $lockedRoom = Room::query()->whereKey($room->id)->lockForUpdate()->firstOrFail();

            $hasActiveStay = Stay::query()
                ->where('room_id', $lockedRoom->id)
                ->where('status', 'in_house')
                ->whereNull('check_out_time')
                ->lockForUpdate()
                ->exists();

            if ($hasActiveStay) {
                throw new \RuntimeException('Cannot block a room with an active in-house stay.');
            }

            $lockedRoom->status = 'out_of_service';
            $lockedRoom->save();
        });

        app(RoomRackService::class)->bustSnapshotCache();

        return 'Room blocked and marked out of order.';
    }

    public function unblock(Room $room): string
    {
        DB::transaction(function () use ($room) {
            $lockedRoom = Room::query()->whereKey($room->id)->lockForUpdate()->firstOrFail();

            $hasActiveStay = Stay::query()
                ->where('room_id', $lockedRoom->id)
                ->where('status', 'in_house')
                ->whereNull('check_out_time')
                ->lockForUpdate()
                ->exists();

            if ($hasActiveStay) {
                throw new \RuntimeException('Cannot unblock while room has an active stay.');
            }

            if (($lockedRoom->status ?? null) !== 'out_of_service') {
                throw new \RuntimeException('Room is not currently blocked.');
            }

            $lockedRoom->status = 'available';
            $lockedRoom->save();
        });

        app(RoomRackService::class)->bustSnapshotCache();

        return 'Room unblocked and available for sale.';
    }

    private function deriveRackStatusForModal(string $rawRoomStatus, bool $hasActiveStay, bool $hasArrivalReservation, bool $hasOpenHousekeeping): array
    {
        if ($hasActiveStay) {
            return ['key' => 'occupied', 'label' => 'Occupied'];
        }

        if ($hasArrivalReservation) {
            return ['key' => 'reserved', 'label' => 'Reserved'];
        }

        if ($rawRoomStatus === 'maintenance') {
            return ['key' => 'maintenance', 'label' => 'Maintenance'];
        }

        if ($rawRoomStatus === 'dirty' || $hasOpenHousekeeping) {
            return ['key' => 'dirty', 'label' => 'Dirty'];
        }

        if ($rawRoomStatus === 'clean') {
            return ['key' => 'clean', 'label' => 'Clean'];
        }

        if ($rawRoomStatus === 'out_of_service') {
            return ['key' => 'out_of_order', 'label' => 'Out of Order'];
        }

        return ['key' => 'available', 'label' => 'Available'];
    }
}

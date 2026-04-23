<?php

namespace App\Services;

use App\Models\HousekeepingTask;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Stay;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class RoomRackService
{
    /**
     * Build the room rack snapshot for a given date.
     *
     * Business priority order:
     * - Occupied: any stay with status = in_house
     * - Reserved: reservation check_in_date = today, and no active stay
     * - Dirty: room.status = dirty OR open housekeeping task
     * - Out of Order: room.status = out_of_service
     * - Available: fallback
     */
    public function build(Carbon $date): array
    {
        $rackDate = $date->copy()->startOfDay();
        $today = Carbon::today()->startOfDay();

        $rooms = Room::query()
            ->select(['rooms.id', 'rooms.room_number', 'rooms.room_type_id', 'rooms.floor_id', 'rooms.status', 'rooms.is_active'])
            ->with([
                'roomType:id,name',
                'floor:id,name,level_number',
                'stays' => function ($query) {
                    $query
                        ->select(['stays.id', 'stays.room_id', 'stays.reservation_id', 'stays.check_in_time', 'stays.check_out_time', 'stays.status'])
                        ->where('stays.status', 'in_house')
                        ->with([
                            'reservation:id,guest_id,reservation_code,check_in_date,check_out_date',
                            'reservation.guest:id,first_name,last_name,vip,phone',
                        ])
                        ->orderByDesc('stays.check_in_time')
                        ->orderByDesc('stays.id');
                },
                'reservations' => function ($query) use ($today) {
                    $query
                        ->select([
                            'reservations.id',
                            'reservations.guest_id',
                            'reservations.reservation_code',
                            'reservations.check_in_date',
                            'reservations.check_out_date',
                            'reservations.status',
                        ])
                        ->whereIn('reservations.status', ['booked', 'confirmed'])
                        ->whereDate('reservations.check_in_date', '=', $today)
                        ->with([
                            'guest:id,first_name,last_name,vip,phone',
                        ])
                        ->orderBy('reservations.check_in_date')
                        ->orderBy('reservations.id');
                },
                'housekeepingTasks' => function ($query) use ($rackDate) {
                    $query
                        ->select(['id', 'room_id', 'task_date', 'status', 'priority'])
                        ->whereIn('status', ['pending', 'in_progress'])
                        ->whereBetween('task_date', [$rackDate->copy()->startOfDay(), $rackDate->copy()->endOfDay()]);
                },
            ])
            ->where('rooms.is_active', true)
            ->orderByRaw('CAST((SELECT level_number FROM floors WHERE floors.id = rooms.floor_id) AS INTEGER) asc')
            ->orderBy('rooms.room_number')
            ->get();

        $floors = $rooms
            ->groupBy(fn (Room $room) => (int) ($room->floor_id ?? 0))
            ->map(function (Collection $floorRooms) use ($rackDate) {
                $first = $floorRooms->first();
                $floor = $first?->floor;
                $label = $floor?->name;

                if (!$label || trim((string) $label) === '') {
                    $label = $floor?->level_number ? ('Floor ' . $floor->level_number) : 'Unassigned Floor';
                } elseif ($floor?->level_number) {
                    $label = $label . ' (L' . $floor->level_number . ')';
                }

                return [
                    'floor_id' => (int) ($first?->floor_id ?? 0),
                    'floor_label' => $label,
                    'rooms' => $floorRooms
                        ->map(function (Room $room) use ($rackDate) {
                            $activeStay = $room->stays->first();
                            $reservation = $room->reservations->first();
                            $hk = $this->pickOpenHousekeepingTask($room->housekeepingTasks);

                            $derived = $this->deriveStatus($room, $activeStay, $reservation, $hk);

                            $guestName = null;
                            $reservationCode = null;
                            $checkInDate = null;
                            $checkOutDate = null;
                            $isVip = false;
                            $isOverstay = false;

                            if ($activeStay && $activeStay->reservation) {
                                $reservationCode = $activeStay->reservation->reservation_code;
                                $checkInDate = $activeStay->reservation->check_in_date;
                                $checkOutDate = $activeStay->reservation->check_out_date;
                                $guest = $activeStay->reservation->guest;
                                if ($guest) {
                                    $guestName = trim(($guest->first_name ?? '') . ' ' . ($guest->last_name ?? '')) ?: null;
                                    $isVip = (bool) ($guest->vip ?? false);
                                }

                                if ($checkOutDate) {
                                    $isOverstay = $rackDate->gt(Carbon::parse($checkOutDate)->startOfDay());
                                }
                            } elseif ($reservation) {
                                $reservationCode = $reservation->reservation_code;
                                $checkInDate = $reservation->check_in_date;
                                $checkOutDate = $reservation->check_out_date;
                                $guest = $reservation->guest;
                                if ($guest) {
                                    $guestName = trim(($guest->first_name ?? '') . ' ' . ($guest->last_name ?? '')) ?: null;
                                    $isVip = (bool) ($guest->vip ?? false);
                                }
                            }

                            return [
                                'id' => $room->id,
                                'room_number' => (string) ($room->room_number ?? '-'),
                                'room_type' => (string) ($room->roomType?->name ?? '-'),
                                'floor_label' => (string) ($room->floor?->name ?? ''),
                                'raw_room_status' => (string) ($room->status ?? ''),

                                'rack_status' => $derived['status'],
                                'rack_status_label' => $derived['label'],
                                'rack_badge_class' => $derived['badge_class'],

                                'guest_name' => $guestName,
                                'reservation_code' => $reservationCode,
                                'check_in_date' => $checkInDate,
                                'check_out_date' => $checkOutDate,

                                'housekeeping_status' => $hk?->status,
                                'housekeeping_priority' => $hk?->priority,

                                'is_vip' => $isVip,
                                'is_overstay' => $isOverstay,
                            ];
                        })
                        ->values()
                        ->all(),
                ];
            })
            ->sortBy('floor_id')
            ->values()
            ->all();

        $counts = [
            'available' => 0,
            'occupied' => 0,
            'reserved' => 0,
            'dirty' => 0,
            'out_of_order' => 0,
            'other' => 0,
        ];

        foreach ($floors as $floor) {
            foreach ($floor['rooms'] as $room) {
                $key = $room['rack_status'] ?? 'other';
                if (!array_key_exists($key, $counts)) {
                    $key = 'other';
                }
                $counts[$key]++;
            }
        }

        return [
            'rackDate' => $rackDate,
            'floors' => $floors,
            'counts' => $counts,
            'generatedAt' => now(),
        ];
    }

    private function pickOpenHousekeepingTask(Collection $tasks): ?HousekeepingTask
    {
        $priorityRank = ['high' => 3, 'medium' => 2, 'low' => 1];
        $statusRank = ['in_progress' => 2, 'pending' => 1];

        return $tasks
            ->sort(function ($a, $b) use ($priorityRank, $statusRank) {
                $sa = $statusRank[$a->status] ?? 0;
                $sb = $statusRank[$b->status] ?? 0;
                if ($sa !== $sb) {
                    return $sb <=> $sa;
                }

                $pa = $priorityRank[$a->priority] ?? 0;
                $pb = $priorityRank[$b->priority] ?? 0;
                if ($pa !== $pb) {
                    return $pb <=> $pa;
                }

                return Carbon::parse($a->task_date) <=> Carbon::parse($b->task_date);
            })
            ->first();
    }

    private function deriveStatus(Room $room, ?Stay $activeStay, ?Reservation $reservation, ?HousekeepingTask $housekeeping): array
    {
        $raw = strtolower((string) ($room->status ?? ''));
        $isDirty = $raw === 'dirty' || $housekeeping !== null;
        $isOutOfOrder = $raw === 'out_of_service';

        // Highest priority: active in-house stay.
        if ($activeStay) {
            return ['status' => 'occupied', 'label' => 'Occupied', 'badge_class' => 'kt-badge-warning'];
        }

        // Reserved is only today-arrival reservations and only when not occupied.
        if ($reservation) {
            return ['status' => 'reserved', 'label' => 'Reserved', 'badge_class' => 'kt-badge-outline kt-badge-info'];
        }

        if ($isDirty) {
            return ['status' => 'dirty', 'label' => 'Dirty', 'badge_class' => 'kt-badge-destructive'];
        }

        if ($isOutOfOrder) {
            return ['status' => 'out_of_order', 'label' => 'Out of Order', 'badge_class' => 'kt-badge-outline kt-badge-destructive'];
        }

        return ['status' => 'available', 'label' => 'Available', 'badge_class' => 'kt-badge-success'];
    }
}

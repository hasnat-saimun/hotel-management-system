<?php

namespace App\Services;

use App\Models\Floor;
use App\Models\HousekeepingTask;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Stay;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RoomRackService
{
    /**
     * @return array{q:string,floor_id:?int,room_type_id:?int,status:string}
     */
    public function filtersFromRequest(Request $request): array
    {
        $q = trim((string) $request->query('q', ''));
        $floorId = $request->filled('floor_id') ? (int) $request->query('floor_id') : null;
        $roomTypeId = $request->filled('room_type_id') ? (int) $request->query('room_type_id') : null;
        $status = (string) $request->query('status', 'all');

        if (!in_array($status, ['all', 'available', 'occupied', 'reserved', 'clean', 'dirty', 'maintenance', 'out_of_order'], true)) {
            $status = 'all';
        }

        return [
            'q' => $q,
            'floor_id' => $floorId,
            'room_type_id' => $roomTypeId,
            'status' => $status,
        ];
    }

    /**
     * Build the room rack snapshot for a given date.
     *
     * Business priority order:
     * - Occupied: any stay with status = in_house
     * - Reserved: reservation check_in_date = today, and no active stay
    * - Maintenance: room.status = maintenance
    * - Dirty: room.status = dirty OR open housekeeping task
    * - Clean: room.status = clean
    * - Out of Order: room.status = out_of_service
     * - Available: fallback
     */
    public function build(Carbon $date, array $filters = []): array
    {
        $rackDate = $date->copy()->startOfDay();
        $search = trim((string) ($filters['q'] ?? ''));
        $floorId = $filters['floor_id'] ?? null;
        $roomTypeId = $filters['room_type_id'] ?? null;
        $statusFilter = (string) ($filters['status'] ?? 'all');

        $rooms = Room::query()
            ->select(['rooms.id', 'rooms.room_number', 'rooms.room_type_id', 'rooms.floor_id', 'rooms.status', 'rooms.is_active'])
            ->leftJoin('floors as floor_sort', 'floor_sort.id', '=', 'rooms.floor_id')
            ->with([
                'roomType:id,name',
                'floor:id,name,level_number',
                'stays' => function ($query) {
                    $query
                        ->select(['stays.id', 'stays.room_id', 'stays.reservation_id', 'stays.check_in_time', 'stays.check_out_time', 'stays.status'])
                        ->where('stays.status', 'in_house')
                        ->whereNull('stays.check_out_time')
                        ->with([
                            'reservation:id,guest_id,reservation_code,check_in_date,check_out_date',
                            'reservation.guest:id,first_name,last_name,vip,phone',
                        ])
                        ->orderByDesc('stays.check_in_time')
                        ->orderByDesc('stays.id');
                },
                'reservations' => function ($query) use ($rackDate) {
                    $query
                        ->select([
                            'reservations.id',
                            'reservations.guest_id',
                            'reservations.reservation_code',
                            'reservations.check_in_date',
                            'reservations.check_out_date',
                            'reservations.status',
                            'reservations.channel',
                        ])
                        ->whereIn('reservations.status', ['booked', 'confirmed'])
                        ->whereDate('reservations.check_in_date', '=', $rackDate)
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
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where('rooms.room_number', 'like', '%' . $search . '%');
            })
            ->when($floorId, function (Builder $query) use ($floorId) {
                $query->where('rooms.floor_id', $floorId);
            })
            ->when($roomTypeId, function (Builder $query) use ($roomTypeId) {
                $query->where('rooms.room_type_id', $roomTypeId);
            })
            ->when($statusFilter !== 'all', function (Builder $query) use ($statusFilter, $rackDate) {
                $this->applyStatusFilter($query, $statusFilter, $rackDate);
            })
            ->orderByRaw('COALESCE(floor_sort.level_number, 9999) asc')
            ->orderBy('rooms.room_number')
            ->orderBy('rooms.id')
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
                            $guestPhone = null;
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
                                    $guestPhone = $guest->phone ?? null;
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
                                    $guestPhone = $guest->phone ?? null;
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
                                'guest_phone' => $guestPhone,
                                'reservation_code' => $reservationCode,
                                'check_in_date' => $checkInDate,
                                'check_out_date' => $checkOutDate,

                                'reservation_id' => $reservation?->id,
                                'reservation_status' => $reservation?->status,
                                'reservation_channel' => $reservation?->channel,

                                'stay_id' => $activeStay?->id,
                                'stay_status' => $activeStay?->status,
                                'stay_check_in_time' => $activeStay?->check_in_time,
                                'stay_check_out_time' => $activeStay?->check_out_time,

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
            'clean' => 0,
            'dirty' => 0,
            'maintenance' => 0,
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
            'filters' => $filters,
            'roomTypes' => $this->roomTypes(),
            'floorsList' => $this->floors(),
        ];
    }

    public function roomTypes()
    {
        return RoomType::query()
            ->select(['id', 'name'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function floors()
    {
        return Floor::query()
            ->select(['id', 'name', 'level_number'])
            ->orderBy('level_number')
            ->orderBy('name')
            ->get();
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
        $isMaintenance = $raw === 'maintenance';
        $isDirty = $raw === 'dirty' || $housekeeping !== null;
        $isClean = $raw === 'clean';
        $isOutOfOrder = $raw === 'out_of_service';

        // Highest priority: active in-house stay.
        if ($activeStay) {
            return ['status' => 'occupied', 'label' => 'Occupied', 'badge_class' => 'kt-badge-warning'];
        }

        // Reserved is only today-arrival reservations and only when not occupied.
        if ($reservation) {
            return ['status' => 'reserved', 'label' => 'Reserved', 'badge_class' => 'kt-badge-outline kt-badge-info'];
        }

        if ($isMaintenance) {
            return ['status' => 'maintenance', 'label' => 'Maintenance', 'badge_class' => 'kt-badge-outline kt-badge-warning'];
        }

        if ($isDirty) {
            return ['status' => 'dirty', 'label' => 'Dirty', 'badge_class' => 'kt-badge-destructive'];
        }

        if ($isClean) {
            return ['status' => 'clean', 'label' => 'Clean', 'badge_class' => 'kt-badge-success'];
        }

        if ($isOutOfOrder) {
            return ['status' => 'out_of_order', 'label' => 'Out of Order', 'badge_class' => 'kt-badge-outline kt-badge-destructive'];
        }

        return ['status' => 'available', 'label' => 'Available', 'badge_class' => 'kt-badge-success'];
    }

    private function applyStatusFilter(Builder $query, string $statusFilter, Carbon $rackDate): void
    {
        $statusFilter = strtolower($statusFilter);

        if ($statusFilter === 'occupied') {
            $query->whereHas('stays', $this->currentStayConstraint());

            return;
        }

        if ($statusFilter === 'reserved') {
            $query->whereDoesntHave('stays', $this->currentStayConstraint())
                ->whereHas('reservations', function (Builder $reservationQuery) use ($rackDate) {
                    $reservationQuery
                        ->whereIn('reservations.status', ['booked', 'confirmed'])
                        ->whereDate('reservations.check_in_date', '=', $rackDate);
                });

            return;
        }

        if ($statusFilter === 'dirty') {
            $query->whereDoesntHave('stays', $this->currentStayConstraint())
                ->whereDoesntHave('reservations', function (Builder $reservationQuery) use ($rackDate) {
                    $reservationQuery
                        ->whereIn('reservations.status', ['booked', 'confirmed'])
                        ->whereDate('reservations.check_in_date', '=', $rackDate);
                })
                ->where(function (Builder $dirtyQuery) use ($rackDate) {
                    $dirtyQuery
                        ->where('rooms.status', 'dirty')
                        ->orWhereHas('housekeepingTasks', function (Builder $taskQuery) use ($rackDate) {
                            $taskQuery
                                ->whereIn('status', ['pending', 'in_progress'])
                                ->whereBetween('task_date', [$rackDate->copy()->startOfDay(), $rackDate->copy()->endOfDay()]);
                        });
                })
                ->where('rooms.status', '!=', 'out_of_service');

            return;
        }

        if ($statusFilter === 'clean') {
            $query->whereDoesntHave('stays', $this->currentStayConstraint())
                ->whereDoesntHave('reservations', function (Builder $reservationQuery) use ($rackDate) {
                    $reservationQuery
                        ->whereIn('reservations.status', ['booked', 'confirmed'])
                        ->whereDate('reservations.check_in_date', '=', $rackDate);
                })
                ->whereDoesntHave('housekeepingTasks', function (Builder $taskQuery) use ($rackDate) {
                    $taskQuery
                        ->whereIn('status', ['pending', 'in_progress'])
                        ->whereBetween('task_date', [$rackDate->copy()->startOfDay(), $rackDate->copy()->endOfDay()]);
                })
                ->where('rooms.status', 'clean');

            return;
        }

        if ($statusFilter === 'maintenance') {
            $query->whereDoesntHave('stays', $this->currentStayConstraint())
                ->whereDoesntHave('reservations', function (Builder $reservationQuery) use ($rackDate) {
                    $reservationQuery
                        ->whereIn('reservations.status', ['booked', 'confirmed'])
                        ->whereDate('reservations.check_in_date', '=', $rackDate);
                })
                ->where('rooms.status', 'maintenance');

            return;
        }

        if ($statusFilter === 'out_of_order') {
            $query->whereDoesntHave('stays', $this->currentStayConstraint())
                ->whereDoesntHave('reservations', function (Builder $reservationQuery) use ($rackDate) {
                    $reservationQuery
                        ->whereIn('reservations.status', ['booked', 'confirmed'])
                        ->whereDate('reservations.check_in_date', '=', $rackDate);
                })
                ->where('rooms.status', 'out_of_service');

            return;
        }

        if ($statusFilter === 'available') {
            $query->where('rooms.status', '!=', 'out_of_service')
                ->where('rooms.status', 'available')
                ->where('rooms.status', '!=', 'dirty')
                ->where('rooms.status', '!=', 'occupied')
                ->where('rooms.status', '!=', 'clean')
                ->where('rooms.status', '!=', 'maintenance')
                ->whereDoesntHave('stays', $this->currentStayConstraint())
                ->whereDoesntHave('reservations', function (Builder $reservationQuery) use ($rackDate) {
                    $reservationQuery
                        ->whereIn('reservations.status', ['booked', 'confirmed'])
                        ->whereDate('reservations.check_in_date', '=', $rackDate);
                })
                ->whereDoesntHave('housekeepingTasks', function (Builder $taskQuery) use ($rackDate) {
                    $taskQuery
                        ->whereIn('status', ['pending', 'in_progress'])
                        ->whereBetween('task_date', [$rackDate->copy()->startOfDay(), $rackDate->copy()->endOfDay()]);
                });
        }
    }

    /**
     * Match only a currently occupied stay.
     */
    private function currentStayConstraint(): \Closure
    {
        return function (Builder $stayQuery): void {
            $stayQuery
                ->where('stays.status', 'in_house')
                ->whereNull('stays.check_out_time');
        };
    }
}

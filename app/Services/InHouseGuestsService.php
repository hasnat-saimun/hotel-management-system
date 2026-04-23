<?php

namespace App\Services;

use App\Models\Floor;
use App\Models\RoomType;
use App\Models\Stay;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class InHouseGuestsService
{
    /**
     * @return array{q:string,room_type_id:?int,floor_id:?int,status:string}
     */
    public function filtersFromRequest(Request $request): array
    {
        $q = trim((string) $request->query('q', ''));
        $roomTypeId = $request->filled('room_type_id') ? (int) $request->query('room_type_id') : null;
        $floorId = $request->filled('floor_id') ? (int) $request->query('floor_id') : null;
        $status = (string) $request->query('status', 'all');

        if (!in_array($status, ['all', 'overstay', 'vip'], true)) {
            $status = 'all';
        }

        return [
            'q' => $q,
            'room_type_id' => $roomTypeId,
            'floor_id' => $floorId,
            'status' => $status,
        ];
    }

    public function baseQuery(array $filters, Carbon $today): Builder
    {
        $q = (string) ($filters['q'] ?? '');
        $roomTypeId = $filters['room_type_id'] ?? null;
        $floorId = $filters['floor_id'] ?? null;
        $status = (string) ($filters['status'] ?? 'all');

        return Stay::query()
            ->select([
                'stays.id',
                'stays.reservation_id',
                'stays.room_id',
                'stays.check_in_time',
                'stays.check_out_time',
                'stays.status',
                'stays.adults',
                'stays.children',
            ])
            ->where('stays.status', 'in_house')
            ->with([
                'reservation:id,guest_id,check_out_date',
                'reservation.guest:id,first_name,last_name,phone,vip',
                'room:id,room_number,room_type_id,floor_id,status',
                'room.roomType:id,name',
                'room.floor:id,name,level_number',
            ])
            ->when($q !== '', function (Builder $query) use ($q) {
                $query->whereHas('reservation.guest', function (Builder $guestQuery) use ($q) {
                    $guestQuery
                        ->where('first_name', 'like', '%' . $q . '%')
                        ->orWhere('last_name', 'like', '%' . $q . '%')
                        ->orWhere('phone', 'like', '%' . $q . '%');
                });
            })
            ->when($roomTypeId, function (Builder $query) use ($roomTypeId) {
                $query->whereHas('room', fn (Builder $roomQuery) => $roomQuery->where('room_type_id', $roomTypeId));
            })
            ->when($floorId, function (Builder $query) use ($floorId) {
                $query->whereHas('room', fn (Builder $roomQuery) => $roomQuery->where('floor_id', $floorId));
            })
            ->when($status === 'vip', function (Builder $query) {
                $query->whereHas('reservation.guest', fn (Builder $guestQuery) => $guestQuery->where('vip', true));
            })
            ->when($status === 'overstay', function (Builder $query) use ($today) {
                $query->whereHas('reservation', fn (Builder $reservationQuery) => $reservationQuery->whereDate('check_out_date', '<', $today));
            })
            ->orderByDesc('stays.check_in_time')
            ->orderByDesc('stays.id');
    }

    public function paginate(array $filters, Carbon $today, int $perPage = 15): LengthAwarePaginator
    {
        return $this->baseQuery($filters, $today)
            ->paginate($perPage)
            ->withQueryString();
    }

    public function attachComputedFields(LengthAwarePaginator $stays, Carbon $today, \DateTimeInterface $now): LengthAwarePaginator
    {
        $stays->getCollection()->transform(function (Stay $stay) use ($today, $now) {
            $nowCarbon = Carbon::instance($now);

            $checkIn = $stay->check_in_time ? Carbon::parse($stay->check_in_time) : null;
            $nightsStayed = $checkIn
                ? $checkIn->copy()->startOfDay()->diffInDays($nowCarbon->copy()->startOfDay())
                : 0;

            $expectedCheckOut = $stay->reservation?->check_out_date
                ? Carbon::parse($stay->reservation->check_out_date)->startOfDay()
                : null;

            $isOverstay = $expectedCheckOut ? $today->copy()->startOfDay()->gt($expectedCheckOut) : false;
            $isVip = (bool) ($stay->reservation?->guest?->vip ?? false);

            $stay->setAttribute('nights_stayed', $nightsStayed);
            $stay->setAttribute('expected_check_out_date', $expectedCheckOut);
            $stay->setAttribute('is_overstay', $isOverstay);
            $stay->setAttribute('is_vip', $isVip);

            return $stay;
        });

        return $stays;
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
}

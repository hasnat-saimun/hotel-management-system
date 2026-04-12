<?php

namespace App\Services;

use App\Models\Guest;
use App\Models\Reservation;
use App\Models\ReservationRoom;
use App\Models\Room;
use App\Models\RoomBlock;
use App\Models\RoomBlockRoom;
use App\Models\Folio;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RoomBlockService
{
    public function __construct(private readonly RoomAvailabilityService $availability)
    {
    }

    public function createBlock(array $data): RoomBlock
    {
        return DB::transaction(function () use ($data) {
            $startDate = Carbon::parse($data['start_date'])->toDateString();
            $endDate = Carbon::parse($data['end_date'])->toDateString();

            if ($endDate <= $startDate) {
                throw ValidationException::withMessages([
                    'end_date' => 'End date must be after start date.',
                ]);
            }

            $requestedRoomIds = collect($data['room_ids'] ?? [])->filter()->map(fn ($v) => (int) $v)->unique()->values();
            $roomTypeId = !empty($data['room_type_id']) ? (int) $data['room_type_id'] : null;
            $totalRooms = (int) ($data['total_rooms'] ?? 0);

            if ($requestedRoomIds->isNotEmpty()) {
                $totalRooms = $requestedRoomIds->count();
            }

            if ($totalRooms < 1) {
                throw ValidationException::withMessages([
                    'total_rooms' => 'Total rooms must be at least 1.',
                ]);
            }

            if ($requestedRoomIds->isEmpty() && !$roomTypeId) {
                throw ValidationException::withMessages([
                    'room_type_id' => 'Room type is required when auto-assigning rooms.',
                ]);
            }

            $block = RoomBlock::create([
                'group_name' => (string) ($data['group_name'] ?? ''),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_rooms' => $totalRooms,
                'status' => $data['status'] ?? 'tentative',
                'release_at' => $data['release_at'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            $roomIdsToAssign = $requestedRoomIds;
            if ($roomIdsToAssign->isEmpty()) {
                $roomIdsToAssign = $this->pickAvailableRooms($startDate, $endDate, $roomTypeId, $totalRooms, ignoreRoomBlockId: null);
            }

            $this->attachRoomsToBlock($block, $roomIdsToAssign->all());

            return $block;
        });
    }

    public function attachRoomsToBlock(RoomBlock $block, array $roomIds): void
    {
        $startDate = $block->start_date->toDateString();
        $endDate = $block->end_date->toDateString();

        DB::transaction(function () use ($block, $roomIds, $startDate, $endDate) {
            $roomIds = collect($roomIds)->filter()->map(fn ($v) => (int) $v)->unique()->values();
            if ($roomIds->isEmpty()) {
                return;
            }

            // Lock candidate rooms to avoid double assignment in concurrent operations
            $rooms = Room::query()
                ->whereIn('id', $roomIds)
                ->lockForUpdate()
                ->get();

            $missing = $roomIds->diff($rooms->pluck('id'));
            if ($missing->isNotEmpty()) {
                throw ValidationException::withMessages([
                    'room_ids' => 'One or more rooms were not found.',
                ]);
            }

            foreach ($rooms as $room) {
                // Ensure availability (excluding this block)
                $available = Room::query()->whereKey($room->id);
                $this->availability->constrainToAvailableRooms($available, $startDate, $endDate, false, $block->id);
                $isAvailable = $available->lockForUpdate()->exists();
                if (!$isAvailable) {
                    throw ValidationException::withMessages([
                        'room_ids' => "Room {$room->room_number} is not available for the selected dates.",
                    ]);
                }

                RoomBlockRoom::updateOrCreate(
                    [
                        'room_block_id' => $block->id,
                        'room_id' => $room->id,
                    ],
                    [
                        'room_type_id' => $room->room_type_id,
                        'status' => 'blocked',
                    ]
                );
            }

            $block->total_rooms = (int) $block->roomBlockRooms()->where('status', 'blocked')->count();
            $block->save();
        });
    }

    public function unassignRooms(RoomBlock $block, array $roomBlockRoomIds): void
    {
        DB::transaction(function () use ($block, $roomBlockRoomIds) {
            $ids = collect($roomBlockRoomIds)->filter()->map(fn ($v) => (int) $v)->unique()->values();
            if ($ids->isEmpty()) {
                return;
            }

            $rows = RoomBlockRoom::query()
                ->where('room_block_id', $block->id)
                ->whereIn('id', $ids)
                ->lockForUpdate()
                ->get();

            $notAllowed = $rows->firstWhere('status', 'converted');
            if ($notAllowed) {
                throw ValidationException::withMessages([
                    'room_block_room_ids' => 'Converted rooms cannot be unassigned from a block.',
                ]);
            }

            RoomBlockRoom::where('room_block_id', $block->id)
                ->whereIn('id', $ids)
                ->delete();

            $block->total_rooms = (int) $block->roomBlockRooms()->where('status', 'blocked')->count();
            $block->save();
        });
    }

    public function releaseBlock(RoomBlock $block): void
    {
        DB::transaction(function () use ($block) {
            $block->released_at = now();
            $block->save();
        });
    }

    public function pickAvailableRooms(string $startDate, string $endDate, int $roomTypeId, int $count, ?int $ignoreRoomBlockId): \Illuminate\Support\Collection
    {
        $query = Room::query()
            ->where('room_type_id', $roomTypeId)
            ->orderBy('room_number')
            ->lockForUpdate();

        $this->availability->constrainToAvailableRooms($query, $startDate, $endDate, false, $ignoreRoomBlockId);

        $roomIds = $query->limit($count)->pluck('id');

        if ($roomIds->count() < $count) {
            throw ValidationException::withMessages([
                'total_rooms' => 'Not enough rooms available to fulfill this block.',
            ]);
        }

        return $roomIds;
    }

    /**
     * Converts some or all blocked rooms into 1-reservation-per-room.
     *
     * $assignments format:
     * - key: room_block_room_id
     * - value: ['guest_id' => int|null, 'guest' => ['first_name'=>..,'last_name'=>..,'email'=>..,'phone'=>..] ]
     */
    public function convertToReservations(RoomBlock $block, array $roomBlockRoomIds, array $assignments = []): array
    {
        return DB::transaction(function () use ($block, $roomBlockRoomIds, $assignments) {
            $ids = collect($roomBlockRoomIds)->filter()->map(fn ($v) => (int) $v)->unique()->values();
            if ($ids->isEmpty()) {
                throw ValidationException::withMessages([
                    'room_block_room_ids' => 'Select at least one room to convert.',
                ]);
            }

            $blockRooms = RoomBlockRoom::query()
                ->where('room_block_id', $block->id)
                ->whereIn('id', $ids)
                ->with(['room.roomType', 'assignedGuest'])
                ->lockForUpdate()
                ->get();

            if ($blockRooms->count() !== $ids->count()) {
                throw ValidationException::withMessages([
                    'room_block_room_ids' => 'One or more selected block rooms were not found.',
                ]);
            }

            $alreadyConverted = $blockRooms->firstWhere('status', 'converted');
            if ($alreadyConverted) {
                throw ValidationException::withMessages([
                    'room_block_room_ids' => 'One or more selected rooms are already converted.',
                ]);
            }

            $startDate = $block->start_date->toDateString();
            $endDate = $block->end_date->toDateString();
            $nights = max(1, Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)));

            $createdReservationIds = [];

            foreach ($blockRooms as $blockRoom) {
                $room = $blockRoom->room;
                if (!$room) {
                    throw ValidationException::withMessages([
                        'room_block_room_ids' => 'Block has unassigned rooms; assign rooms before converting.',
                    ]);
                }

                // Lock the room row & re-check availability against reservations (blocks are ignored for this room's own block)
                $roomLocked = Room::query()->whereKey($room->id)->lockForUpdate()->first();
                if (!$roomLocked) {
                    throw ValidationException::withMessages([
                        'room_block_room_ids' => 'Room not found during conversion.',
                    ]);
                }

                $roomAvailable = Room::query()->whereKey($room->id);
                $this->availability->constrainToAvailableRooms($roomAvailable, $startDate, $endDate, true, null);
                if (!$roomAvailable->lockForUpdate()->exists()) {
                    throw ValidationException::withMessages([
                        'room_block_room_ids' => "Room {$room->room_number} is no longer available (already booked).",
                    ]);
                }

                $assignment = $assignments[$blockRoom->id] ?? [];
                $guest = $this->resolveGuestForConversion($blockRoom, $assignment);

                $reservation = Reservation::create([
                    'guest_id' => $guest->id,
                    'room_block_id' => $block->id,
                    'reservation_code' => Str::upper(Str::random(8)),
                    'channel' => 'room_block',
                    'status' => 'confirmed',
                    'payment_status' => 'unpaid',
                    'check_in_date' => $startDate,
                    'check_out_date' => $endDate,
                    'adults' => 1,
                    'children' => 0,
                    'note' => null,
                ]);

                // Multi-guest support: attach primary guest
                $reservation->guests()->syncWithoutDetaching([
                    $guest->id => ['is_primary' => true],
                ]);

                $roomType = $room->roomType;
                $nightlyRate = (float) ($roomType?->base_price ?? 0);
                $discountPerNight = 0.0;
                $taxPerNight = 0.0;
                $total = (($nightlyRate - $discountPerNight) + $taxPerNight) * $nights;

                ReservationRoom::create([
                    'room_id' => $room->id,
                    'reservation_id' => $reservation->id,
                    'room_type_id' => $room->room_type_id,
                    'rate_plan_named' => $assignment['rate_plan_named'] ?? 'Group Rate',
                    'nightly_rate' => $nightlyRate,
                    'discount_amount' => $discountPerNight * $nights,
                    'tax_amount' => $taxPerNight * $nights,
                    'total_amount' => $total,
                    'status' => 'reserved',
                ]);

                // Mark room as reserved in operational status
                $roomLocked->status = 'reserved';
                $roomLocked->save();

                Folio::create([
                    'reservation_id' => $reservation->id,
                    'guest_id' => $guest->id,
                    'room_charge_total' => $nightlyRate * $nights,
                    'discount_total' => $discountPerNight * $nights,
                    'tax_total' => $taxPerNight * $nights,
                    'grand_total' => $total,
                    'status' => 'open',
                    'meta' => [
                        'nights' => $nights,
                        'rate_plan' => $assignment['rate_plan_named'] ?? 'Group Rate',
                    ],
                ]);

                $blockRoom->assigned_guest_id = $guest->id;
                $blockRoom->status = 'converted';
                $blockRoom->save();

                $createdReservationIds[] = $reservation->id;
            }

            return $createdReservationIds;
        });
    }

    private function resolveGuestForConversion(RoomBlockRoom $blockRoom, array $assignment): Guest
    {
        if (!empty($assignment['guest_id'])) {
            $guest = Guest::find((int) $assignment['guest_id']);
            if ($guest) {
                return $guest;
            }
        }

        if ($blockRoom->assignedGuest) {
            return $blockRoom->assignedGuest;
        }

        $guestPayload = $assignment['guest'] ?? null;
        if (is_array($guestPayload)) {
            $email = (string) ($guestPayload['email'] ?? '');
            if ($email !== '' && ($existing = Guest::where('email', $email)->first())) {
                return $existing;
            }

            if (!empty($guestPayload['first_name']) && !empty($guestPayload['last_name']) && $email !== '') {
                return Guest::create([
                    'first_name' => $guestPayload['first_name'],
                    'last_name' => $guestPayload['last_name'],
                    'email' => $email,
                    'phone' => $guestPayload['phone'] ?? null,
                ]);
            }
        }

        throw ValidationException::withMessages([
            'guest' => 'Guest assignment is required for conversion.',
        ]);
    }
}

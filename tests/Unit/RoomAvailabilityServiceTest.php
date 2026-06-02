<?php

namespace Tests\Unit;

use App\Models\Reservation;
use App\Models\ReservationRoom;
use App\Models\Room;
use App\Models\Stay;
use App\Services\RoomAvailabilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomAvailabilityServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_room_is_not_available_when_an_active_stay_overlaps_the_requested_range(): void
    {
        $room = Room::factory()->create(['status' => 'available']);

        Stay::factory()->create([
            'room_id' => $room->id,
            'status' => 'in_house',
            'check_in_time' => now()->subDay(),
            'check_out_time' => null,
        ]);

        $availability = app(RoomAvailabilityService::class);

        $this->assertFalse($availability->isRoomAvailableForRange(
            $room->id,
            now()->toDateString(),
            now()->addDay()->toDateString()
        ));
    }

    public function test_room_is_not_available_when_an_active_reservation_overlaps_the_requested_range(): void
    {
        $room = Room::factory()->create(['status' => 'available']);
        $reservation = Reservation::factory()->create([
            'status' => 'confirmed',
            'check_in_date' => now()->toDateString(),
            'check_out_date' => now()->addDays(2)->toDateString(),
        ]);

        ReservationRoom::create([
            'reservation_id' => $reservation->id,
            'room_id' => $room->id,
            'room_type_id' => $room->room_type_id,
            'status' => 'reserved',
        ]);

        $availability = app(RoomAvailabilityService::class);

        $this->assertFalse($availability->isRoomAvailableForRange(
            $room->id,
            now()->addDay()->toDateString(),
            now()->addDays(3)->toDateString()
        ));
    }

    public function test_room_is_available_when_existing_records_only_touch_the_boundaries(): void
    {
        $room = Room::factory()->create(['status' => 'available']);

        $boundaryDate = now()->startOfDay();

        Stay::factory()->create([
            'room_id' => $room->id,
            'status' => 'in_house',
            'check_in_time' => $boundaryDate->copy()->subDays(3),
            'check_out_time' => $boundaryDate->copy()->toDateTimeString(),
        ]);

        $reservation = Reservation::factory()->create([
            'status' => 'confirmed',
            'check_in_date' => $boundaryDate->copy()->subDays(2)->toDateString(),
            'check_out_date' => $boundaryDate->toDateString(),
        ]);

        ReservationRoom::create([
            'reservation_id' => $reservation->id,
            'room_id' => $room->id,
            'room_type_id' => $room->room_type_id,
            'status' => 'reserved',
        ]);

        $availability = app(RoomAvailabilityService::class);

        $this->assertTrue($availability->isRoomAvailableForRange(
            $room->id,
            $boundaryDate->toDateString(),
            $boundaryDate->copy()->addDay()->toDateString()
        ));
    }
}
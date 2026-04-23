<?php

namespace Tests\Feature\Admin;

use App\Models\HousekeepingTask;
use App\Models\Reservation;
use App\Models\ReservationRoom;
use App\Models\Room;
use App\Models\Stay;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomRackTest extends TestCase
{
    use RefreshDatabase;

    public function test_room_rack_page_loads_and_shows_room_tiles(): void
    {
        $user = User::factory()->create();

        $room = Room::factory()->create([
            'status' => 'available',
        ]);

        $reservation = Reservation::factory()->create([
            'check_in_date' => now()->startOfDay()->toDateString(),
            'check_out_date' => now()->startOfDay()->addDay()->toDateString(),
            'status' => 'confirmed',
        ]);

        // Attach the reservation to the room to mark it reserved for the rack date.
        ReservationRoom::create([
            'reservation_id' => $reservation->id,
            'room_id' => $room->id,
            'room_type_id' => $room->room_type_id,
            'status' => 'reserved',
        ]);

        // Add an open housekeeping task for today.
        HousekeepingTask::create([
            'room_id' => $room->id,
            'task_date' => now(),
            'status' => 'pending',
            'priority' => 'medium',
            'assigned_to' => $user->id,
        ]);

        // Active stay should override reservation/housekeeping as Occupied.
        Stay::factory()->create([
            'room_id' => $room->id,
            'reservation_id' => $reservation->id,
            'status' => 'in_house',
            'check_out_time' => null,
        ]);

        $response = $this->actingAs($user)->get(route('admin.front-desk.room-rack'));

        $response->assertOk();
        $response->assertSee('Room Rack');
        $response->assertSee((string) $room->room_number);
        $response->assertSee('Occupied');
    }

    public function test_reserved_when_today_arrival_and_no_active_stay(): void
    {
        $user = User::factory()->create();
        $room = Room::factory()->create(['status' => 'available']);

        $reservation = Reservation::factory()->create([
            'check_in_date' => today()->toDateString(),
            'check_out_date' => today()->addDay()->toDateString(),
            'status' => 'confirmed',
        ]);

        ReservationRoom::create([
            'reservation_id' => $reservation->id,
            'room_id' => $room->id,
            'room_type_id' => $room->room_type_id,
            'status' => 'reserved',
        ]);

        $response = $this->actingAs($user)->get(route('admin.front-desk.room-rack'));

        $response->assertOk();
        $response->assertSee((string) $room->room_number);
        $response->assertSee('Reserved');
    }

    public function test_dirty_when_housekeeping_is_open_without_stay_or_today_arrival(): void
    {
        $user = User::factory()->create();
        $room = Room::factory()->create(['status' => 'available']);

        HousekeepingTask::create([
            'room_id' => $room->id,
            'task_date' => now(),
            'status' => 'pending',
            'priority' => 'medium',
            'assigned_to' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('admin.front-desk.room-rack'));

        $response->assertOk();
        $response->assertSee((string) $room->room_number);
        $response->assertSee('Dirty');
    }

    public function test_out_of_order_when_room_status_is_out_of_service(): void
    {
        $user = User::factory()->create();
        $room = Room::factory()->create(['status' => 'out_of_service']);

        $response = $this->actingAs($user)->get(route('admin.front-desk.room-rack'));

        $response->assertOk();
        $response->assertSee((string) $room->room_number);
        $response->assertSee('Out of Order');
    }

    public function test_available_when_no_stay_no_today_arrival_no_dirty_and_not_blocked(): void
    {
        $user = User::factory()->create();
        $room = Room::factory()->create(['status' => 'available']);

        $response = $this->actingAs($user)->get(route('admin.front-desk.room-rack'));

        $response->assertOk();
        $response->assertSee((string) $room->room_number);
        $response->assertSee('Available');
    }
}

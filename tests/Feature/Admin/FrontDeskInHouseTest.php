<?php

namespace Tests\Feature\Admin;

use App\Models\ReservationRoom;
use App\Models\Room;
use App\Models\Stay;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontDeskInHouseTest extends TestCase
{
    use RefreshDatabase;

    public function test_in_house_page_loads_and_renders_basic_data(): void
    {
        $user = User::factory()->create();

        $stay = Stay::factory()->create([
            'status' => 'in_house',
            'check_out_time' => null,
            'check_in_time' => now()->subDays(2),
        ]);

        // Ensure reservation + guest exist and set VIP to verify badge logic.
        $reservation = $stay->reservation;
        $guest = $reservation->guest;
        $guest->vip = true;
        $guest->save();

        // Ensure room relationships are consistent.
        /** @var Room $room */
        $room = $stay->room;
        $room->status = 'occupied';
        $room->save();

        // ReservationRoom is expected by some flows; create it if missing.
        ReservationRoom::query()->firstOrCreate(
            ['reservation_id' => $reservation->id, 'room_id' => $room->id],
            [
                'room_type_id' => $room->room_type_id,
                'status' => 'occupied',
                'nightly_rate' => 100,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'total_amount' => 100,
            ]
        );

        $response = $this->actingAs($user)->get(route('admin.front-desk.in-house'));

        $response->assertOk();
        $response->assertSee('In-House Guests');
        $response->assertSee('VIP');
        $response->assertSee('In-House');
        $response->assertSee((string) $room->room_number);
    }

    public function test_in_house_search_filters_by_guest_phone(): void
    {
        $user = User::factory()->create();

        $stayA = Stay::factory()->create([
            'status' => 'in_house',
            'check_out_time' => null,
        ]);
        $stayB = Stay::factory()->create([
            'status' => 'in_house',
            'check_out_time' => null,
        ]);

        $stayA->reservation->guest->update(['phone' => '111-AAA']);
        $stayB->reservation->guest->update(['phone' => '222-BBB']);

        $response = $this->actingAs($user)->get(route('admin.front-desk.in-house', ['q' => '222']));

        $response->assertOk();
        $response->assertSee('222-BBB');
        $response->assertDontSee('111-AAA');
    }
}

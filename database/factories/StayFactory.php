<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stay>
 */
class StayFactory extends Factory
{
    public function definition(): array
    {
        return [
            'reservation_id' => Reservation::factory(),
            'room_id' => Room::factory(),
            'check_in_time' => now()->subDays(2),
            'check_out_time' => null,
            'status' => 'in_house',
            'adults' => 2,
            'children' => 0,
        ];
    }
}

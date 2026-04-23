<?php

namespace Database\Factories;

use App\Models\Guest;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    public function definition(): array
    {
        $checkIn = now()->startOfDay()->subDay();
        $checkOut = now()->startOfDay()->addDay();

        return [
            'guest_id' => Guest::factory(),
            'reservation_code' => Str::upper(Str::random(8)),
            'channel' => 'walkin',
            'status' => 'confirmed',
            'payment_status' => 'unpaid',
            'check_in_date' => $checkIn->toDateString(),
            'check_out_date' => $checkOut->toDateString(),
            'adults' => 2,
            'children' => 0,
            'rate' => 100,
            'extras' => null,
            'note' => null,
        ];
    }
}

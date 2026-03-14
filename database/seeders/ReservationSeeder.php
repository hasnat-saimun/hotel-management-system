<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $guestIds = DB::table('guests')->pluck('id', 'email');
        $rooms = DB::table('rooms')->select('id', 'room_number', 'room_type_id')->get()->keyBy('room_number');

        $records = [
            ['code' => 'RSV-20260314-001', 'guest_email' => 'john.carter@example.com', 'room_number' => '101', 'channel' => 'Walk-in', 'status' => 'confirmed', 'payment_status' => 'partial', 'check_in_date' => Carbon::now()->subDay()->toDateString(), 'check_out_date' => Carbon::now()->addDay()->toDateString(), 'adults' => 1, 'children' => 0, 'rate' => 50, 'extras' => ['Late Checkout'], 'note' => null],
            ['code' => 'RSV-20260314-002', 'guest_email' => 'emma.stone@example.com', 'room_number' => '201', 'channel' => 'Website', 'status' => 'booked', 'payment_status' => 'unpaid', 'check_in_date' => Carbon::now()->addDays(2)->toDateString(), 'check_out_date' => Carbon::now()->addDays(5)->toDateString(), 'adults' => 2, 'children' => 1, 'rate' => 85, 'extras' => ['Breakfast Buffet'], 'note' => 'Early check-in requested.'],
            ['code' => 'RSV-20260314-003', 'guest_email' => 'liam.nguyen@example.com', 'room_number' => '301', 'channel' => 'OTA', 'status' => 'checked_in', 'payment_status' => 'paid', 'check_in_date' => Carbon::now()->subDays(1)->toDateString(), 'check_out_date' => Carbon::now()->addDays(3)->toDateString(), 'adults' => 2, 'children' => 0, 'rate' => 200, 'extras' => ['Airport Pickup'], 'note' => null],
            ['code' => 'RSV-20260314-004', 'guest_email' => 'olivia.brown@example.com', 'room_number' => '302', 'channel' => 'Corporate', 'status' => 'pending', 'payment_status' => 'unpaid', 'check_in_date' => Carbon::now()->addDays(7)->toDateString(), 'check_out_date' => Carbon::now()->addDays(9)->toDateString(), 'adults' => 2, 'children' => 0, 'rate' => 200, 'extras' => ['Laundry Service'], 'note' => 'Company invoice needed.'],
            ['code' => 'RSV-20260314-005', 'guest_email' => 'noah.silva@example.com', 'room_number' => '103', 'channel' => 'Phone', 'status' => 'checked_out', 'payment_status' => 'paid', 'check_in_date' => Carbon::now()->subDays(6)->toDateString(), 'check_out_date' => Carbon::now()->subDays(2)->toDateString(), 'adults' => 1, 'children' => 0, 'rate' => 85, 'extras' => [], 'note' => null],
            ['code' => 'RSV-20260314-006', 'guest_email' => 'sophia.khan@example.com', 'room_number' => '202', 'channel' => 'Website', 'status' => 'cancelled', 'payment_status' => 'refunded', 'check_in_date' => Carbon::now()->subDays(4)->toDateString(), 'check_out_date' => Carbon::now()->subDay()->toDateString(), 'adults' => 2, 'children' => 1, 'rate' => 125, 'extras' => ['Extra Bed'], 'note' => null, 'cancel_note' => 'Flight cancellation by guest.'],
        ];

        foreach ($records as $record) {
            $guestId = $guestIds[$record['guest_email']] ?? null;
            $room = $rooms[$record['room_number']] ?? null;

            if (!$guestId || !$room) {
                continue;
            }

            DB::table('reservations')->updateOrInsert(
                ['reservation_code' => $record['code']],
                [
                    'guest_id' => $guestId,
                    'channel' => $record['channel'],
                    'status' => $record['status'],
                    'payment_status' => $record['payment_status'],
                    'check_in_date' => $record['check_in_date'],
                    'check_out_date' => $record['check_out_date'],
                    'adults' => $record['adults'],
                    'children' => $record['children'],
                    'rate' => $record['rate'],
                    'extras' => json_encode($record['extras']),
                    'note' => $record['note'],
                    'cancel_note' => $record['cancel_note'] ?? null,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );

            $reservationId = DB::table('reservations')->where('reservation_code', $record['code'])->value('id');
            if (!$reservationId) {
                continue;
            }

            $nights = max(1, Carbon::parse($record['check_in_date'])->diffInDays(Carbon::parse($record['check_out_date'])));
            $discount = $record['status'] === 'cancelled' ? 20 : 0;
            $tax = round(($record['rate'] * $nights - $discount) * 0.10, 2);
            $total = round(($record['rate'] * $nights) - $discount + $tax, 2);

            DB::table('reservation_rooms')->updateOrInsert(
                [
                    'reservation_id' => $reservationId,
                    'room_id' => $room->id,
                ],
                [
                    'room_type_id' => $room->room_type_id,
                    'rate_plan_named' => 'Standard Rate',
                    'nightly_rate' => $record['rate'],
                    'discount_amount' => $discount,
                    'tax_amount' => $tax,
                    'total_amount' => $total,
                    'status' => in_array($record['status'], ['checked_in', 'checked_out'], true) ? 'occupied' : 'reserved',
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservationServiceSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $serviceIds = DB::table('extra_services')->pluck('id', 'name');
        $reservationIds = DB::table('reservations')->pluck('id', 'reservation_code');

        $rows = [
            ['reservation_code' => 'RSV-20260314-001', 'service' => 'Late Checkout', 'quantity' => 1, 'unit_price' => 20],
            ['reservation_code' => 'RSV-20260314-002', 'service' => 'Breakfast Buffet', 'quantity' => 3, 'unit_price' => 12],
            ['reservation_code' => 'RSV-20260314-003', 'service' => 'Airport Pickup', 'quantity' => 1, 'unit_price' => 35],
            ['reservation_code' => 'RSV-20260314-004', 'service' => 'Laundry Service', 'quantity' => 2, 'unit_price' => 8],
            ['reservation_code' => 'RSV-20260314-006', 'service' => 'Extra Bed', 'quantity' => 2, 'unit_price' => 18],
        ];

        foreach ($rows as $row) {
            $reservationId = $reservationIds[$row['reservation_code']] ?? null;
            $serviceId = $serviceIds[$row['service']] ?? null;

            if (!$reservationId || !$serviceId) {
                continue;
            }

            $totalPrice = $row['quantity'] * $row['unit_price'];

            DB::table('reservation_services')->updateOrInsert(
                [
                    'reservation_id' => $reservationId,
                    'extra_service_id' => $serviceId,
                ],
                [
                    'quantity' => $row['quantity'],
                    'unit_price' => $row['unit_price'],
                    'total_price' => $totalPrice,
                    'notes' => null,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }
}

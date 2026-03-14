<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $receivedBy = DB::table('users')->where('email', 'accountant@admin.com')->value('id')
            ?? DB::table('users')->where('email', 'admin@admin.com')->value('id')
            ?? DB::table('users')->value('id');

        if (!$receivedBy) {
            return;
        }

        $reservations = DB::table('reservations')->get()->keyBy('reservation_code');
        $invoices = DB::table('invoices')->get()->keyBy('reservation_id');

        $rows = [
            ['reservation_code' => 'RSV-20260314-001', 'method' => 'cash'],
            ['reservation_code' => 'RSV-20260314-003', 'method' => 'credit_card'],
            ['reservation_code' => 'RSV-20260314-005', 'method' => 'bank_transfer'],
            ['reservation_code' => 'RSV-20260314-006', 'method' => 'debit_card'],
        ];

        foreach ($rows as $row) {
            $reservation = $reservations[$row['reservation_code']] ?? null;
            if (!$reservation) {
                continue;
            }

            $invoice = $invoices[$reservation->id] ?? null;
            if (!$invoice) {
                continue;
            }

            $amount = match ($reservation->payment_status) {
                'paid' => (float) $invoice->grand_total,
                'partial' => (float) $invoice->paid_total,
                'refunded' => (float) $invoice->grand_total,
                default => 0,
            };

            if ($amount <= 0) {
                continue;
            }

            DB::table('payments')->updateOrInsert(
                [
                    'reservation_id' => $reservation->id,
                    'paid_by_guest_id' => $reservation->guest_id,
                    'paid_at' => now()->toDateString(),
                ],
                [
                    'amount' => $amount,
                    'method' => $row['method'],
                    'received_by' => $receivedBy,
                    'notes' => null,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefundSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $refundedBy = DB::table('users')->where('email', 'admin@admin.com')->value('id')
            ?? DB::table('users')->value('id');

        if (!$refundedBy) {
            return;
        }

        $reservationId = DB::table('reservations')->where('reservation_code', 'RSV-20260314-006')->value('id');
        if (!$reservationId) {
            return;
        }

        $invoice = DB::table('invoices')->where('reservation_id', $reservationId)->first();
        $payment = DB::table('payments')->where('reservation_id', $reservationId)->first();

        if (!$invoice || !$payment) {
            return;
        }

        DB::table('refunds')->updateOrInsert(
            [
                'payment_id' => $payment->id,
                'invoice_id' => $invoice->id,
            ],
            [
                'amount' => $payment->amount,
                'refunded_at' => now()->toDateString(),
                'reason' => 'Reservation cancelled by guest due to flight issue.',
                'refunded_by' => $refundedBy,
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );
    }
}

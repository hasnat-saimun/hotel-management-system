<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $createdBy = DB::table('users')->where('email', 'admin@admin.com')->value('id')
            ?? DB::table('users')->value('id');

        if (!$createdBy) {
            return;
        }

        $reservations = DB::table('reservations')->get();

        foreach ($reservations as $index => $reservation) {
            $roomTotal = (float) DB::table('reservation_rooms')
                ->where('reservation_id', $reservation->id)
                ->sum('total_amount');

            $serviceTotal = (float) DB::table('reservation_services')
                ->where('reservation_id', $reservation->id)
                ->sum('total_price');

            $subtotal = round($roomTotal + $serviceTotal, 2);
            $discount = 0;
            $tax = round($subtotal * 0.10, 2);
            $grand = round($subtotal - $discount + $tax, 2);

            $paid = match ($reservation->payment_status) {
                'paid' => $grand,
                'partial' => round($grand * 0.4, 2),
                default => 0,
            };

            $due = max(0, round($grand - $paid, 2));

            DB::table('invoices')->updateOrInsert(
                ['reservation_id' => $reservation->id],
                [
                    'invoice_number' => sprintf('INV-2026-%04d', $index + 1),
                    'guest_id' => $reservation->guest_id,
                    'issued_at' => now()->toDateString(),
                    'due_at' => now()->addDays(7)->toDateString(),
                    'subtotal' => $subtotal,
                    'discount_total' => $discount,
                    'tax_total' => $tax,
                    'grand_total' => $grand,
                    'paid_total' => $paid,
                    'due_total' => $due,
                    'status' => $reservation->payment_status,
                    'notes' => null,
                    'created_by' => $createdBy,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }
}

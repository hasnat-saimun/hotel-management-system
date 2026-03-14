<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceItemSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $invoices = DB::table('invoices')->get();

        foreach ($invoices as $invoice) {
            DB::table('invoice_items')->where('invoice_id', $invoice->id)->delete();

            $reservationRoomItems = DB::table('reservation_rooms')
                ->where('reservation_id', $invoice->reservation_id)
                ->get();

            foreach ($reservationRoomItems as $item) {
                DB::table('invoice_items')->insert([
                    'invoice_id' => $invoice->id,
                    'item_type' => 'room',
                    'description' => 'Room charge',
                    'quantity' => 1,
                    'unit_price' => $item->nightly_rate,
                    'discount_amount' => $item->discount_amount,
                    'tax_amount' => $item->tax_amount,
                    'line_amount' => $item->total_amount,
                    'reference_type' => 'reservation_room',
                    'reference_id' => (string) $item->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $reservationServiceItems = DB::table('reservation_services')
                ->join('extra_services', 'reservation_services.extra_service_id', '=', 'extra_services.id')
                ->where('reservation_services.reservation_id', $invoice->reservation_id)
                ->select('reservation_services.*', 'extra_services.name as service_name')
                ->get();

            foreach ($reservationServiceItems as $item) {
                DB::table('invoice_items')->insert([
                    'invoice_id' => $invoice->id,
                    'item_type' => 'service',
                    'description' => $item->service_name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'discount_amount' => 0,
                    'tax_amount' => 0,
                    'line_amount' => $item->total_price,
                    'reference_type' => 'reservation_service',
                    'reference_id' => (string) $item->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}

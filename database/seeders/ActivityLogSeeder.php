<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $adminId = DB::table('users')->where('email', 'admin@admin.com')->value('id')
            ?? DB::table('users')->value('id');

        if (!$adminId) {
            return;
        }

        $reservationId = DB::table('reservations')->where('reservation_code', 'RSV-20260314-003')->value('id');
        $invoiceId = DB::table('invoices')->where('reservation_id', $reservationId)->value('id');

        $rows = [
            [
                'user_id' => $adminId,
                'action' => 'created_reservation',
                'entity_type' => 'reservation',
                'entity_id' => $reservationId,
                'meta' => json_encode(['reservation_code' => 'RSV-20260314-003']),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => $adminId,
                'action' => 'issued_invoice',
                'entity_type' => 'invoice',
                'entity_id' => $invoiceId,
                'meta' => json_encode(['invoice_entity' => $invoiceId]),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($rows as $row) {
            if (!$row['entity_id']) {
                continue;
            }

            DB::table('activity_logs')->updateOrInsert(
                [
                    'user_id' => $row['user_id'],
                    'action' => $row['action'],
                    'entity_type' => $row['entity_type'],
                    'entity_id' => $row['entity_id'],
                ],
                [
                    'meta' => $row['meta'],
                    'ip_address' => $row['ip_address'],
                    'user_agent' => $row['user_agent'],
                    'updated_at' => $row['updated_at'],
                    'created_at' => $row['created_at'],
                ]
            );
        }
    }
}

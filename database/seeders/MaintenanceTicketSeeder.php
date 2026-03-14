<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaintenanceTicketSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $assignedTo = DB::table('users')->where('email', 'maintenance@admin.com')->value('id')
            ?? DB::table('users')->where('email', 'admin@admin.com')->value('id')
            ?? DB::table('users')->value('id');

        $reportedBy = DB::table('users')->where('email', 'frontdesk@admin.com')->value('id')
            ?? DB::table('users')->where('email', 'admin@admin.com')->value('id')
            ?? DB::table('users')->value('id');

        if (!$assignedTo || !$reportedBy) {
            return;
        }

        $roomIds = DB::table('rooms')->pluck('id', 'room_number');

        $rows = [
            ['room_number' => '203', 'issue_description' => 'AC not cooling properly.', 'status' => 'in_progress', 'priority' => 'high', 'cost' => 45.00, 'resolved_at' => null],
            ['room_number' => '102', 'issue_description' => 'Bathroom tap leakage.', 'status' => 'open', 'priority' => 'medium', 'cost' => 0, 'resolved_at' => null],
            ['room_number' => '301', 'issue_description' => 'Bedroom lamp replacement completed.', 'status' => 'resolved', 'priority' => 'low', 'cost' => 12.50, 'resolved_at' => now()->subDay()],
        ];

        foreach ($rows as $row) {
            $roomId = $roomIds[$row['room_number']] ?? null;
            if (!$roomId) {
                continue;
            }

            DB::table('maintenance_tickets')->updateOrInsert(
                [
                    'room_id' => $roomId,
                    'issue_description' => $row['issue_description'],
                ],
                [
                    'status' => $row['status'],
                    'priority' => $row['priority'],
                    'assigned_to' => $assignedTo,
                    'cost' => $row['cost'],
                    'reported_by' => $reportedBy,
                    'resolved_at' => $row['resolved_at'],
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }
}

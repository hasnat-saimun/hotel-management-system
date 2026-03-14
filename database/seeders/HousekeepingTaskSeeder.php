<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HousekeepingTaskSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $assignedTo = DB::table('users')->where('email', 'housekeeping@admin.com')->value('id')
            ?? DB::table('users')->where('email', 'admin@admin.com')->value('id')
            ?? DB::table('users')->value('id');

        if (!$assignedTo) {
            return;
        }

        $roomIds = DB::table('rooms')->pluck('id', 'room_number');

        $rows = [
            ['room_number' => '101', 'task_date' => now()->subHour(), 'status' => 'done', 'priority' => 'medium', 'notes' => 'Room sanitized and linens changed.', 'completed_at' => now()->subMinutes(30)],
            ['room_number' => '202', 'task_date' => now()->addHour(), 'status' => 'pending', 'priority' => 'high', 'notes' => 'Prepare room before VIP arrival.', 'completed_at' => null],
            ['room_number' => '301', 'task_date' => now()->addHours(2), 'status' => 'in_progress', 'priority' => 'medium', 'notes' => 'Daily refresh while guest is out.', 'completed_at' => null],
        ];

        foreach ($rows as $row) {
            $roomId = $roomIds[$row['room_number']] ?? null;
            if (!$roomId) {
                continue;
            }

            DB::table('housekeeping_tasks')->updateOrInsert(
                [
                    'room_id' => $roomId,
                    'task_date' => $row['task_date'],
                ],
                [
                    'status' => $row['status'],
                    'priority' => $row['priority'],
                    'assigned_to' => $assignedTo,
                    'notes' => $row['notes'],
                    'completed_at' => $row['completed_at'],
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }
}

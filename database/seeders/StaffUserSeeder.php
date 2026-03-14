<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffUserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $users = [
            [
                'name' => 'Front Desk',
                'email' => 'frontdesk@admin.com',
                'password' => Hash::make('password'),
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Housekeeping',
                'email' => 'housekeeping@admin.com',
                'password' => Hash::make('password'),
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Accountant',
                'email' => 'accountant@admin.com',
                'password' => Hash::make('password'),
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Maintenance',
                'email' => 'maintenance@admin.com',
                'password' => Hash::make('password'),
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('users')->upsert(
            $users,
            ['email'],
            ['name', 'password', 'email_verified_at', 'updated_at']
        );
    }
}

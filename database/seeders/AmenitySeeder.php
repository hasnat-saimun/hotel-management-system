<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $list = [
            ['name' => 'WiFi', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Air Conditioning', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Smart TV', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Mini Bar', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Work Desk', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Ocean View', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('amenities')->upsert($list, ['name'], ['is_active', 'updated_at']);
    }
}

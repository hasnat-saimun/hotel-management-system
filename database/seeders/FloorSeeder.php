<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FloorSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $floors = [
            ['name' => 'Ground Floor', 'level_number' => '0', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'First Floor', 'level_number' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Second Floor', 'level_number' => '2', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Third Floor', 'level_number' => '3', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('floors')->upsert($floors, ['level_number'], ['name', 'updated_at']);
    }
}

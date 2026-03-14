<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $types = [
            [
                'name' => 'Single',
                'slug' => Str::slug('Single'),
                'capacity_adults' => 1,
                'capacity_children' => 0,
                'base_price' => 50,
                'description' => 'Comfortable room for solo travelers.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Double',
                'slug' => Str::slug('Double'),
                'capacity_adults' => 2,
                'capacity_children' => 1,
                'base_price' => 85,
                'description' => 'Perfect for couples or small families.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Deluxe',
                'slug' => Str::slug('Deluxe'),
                'capacity_adults' => 2,
                'capacity_children' => 2,
                'base_price' => 125,
                'description' => 'Spacious room with upgraded furnishings.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Suite',
                'slug' => Str::slug('Suite'),
                'capacity_adults' => 4,
                'capacity_children' => 2,
                'base_price' => 200,
                'description' => 'Premium suite with separate living space.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('room_types')->upsert(
            $types,
            ['slug'],
            ['name', 'capacity_adults', 'capacity_children', 'base_price', 'description', 'is_active', 'updated_at']
        );
    }
}

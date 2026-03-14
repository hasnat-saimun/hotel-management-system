<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $roomTypeIds = DB::table('room_types')->pluck('id', 'name');
        $floorIds = DB::table('floors')->pluck('id', 'level_number');

        $rooms = [
            ['room_number' => '101', 'room_type_id' => $roomTypeIds['Single'] ?? null, 'floor_id' => $floorIds['1'] ?? null, 'status' => 'clean', 'notes' => 'Near elevator', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['room_number' => '102', 'room_type_id' => $roomTypeIds['Single'] ?? null, 'floor_id' => $floorIds['1'] ?? null, 'status' => 'available', 'notes' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['room_number' => '103', 'room_type_id' => $roomTypeIds['Double'] ?? null, 'floor_id' => $floorIds['1'] ?? null, 'status' => 'available', 'notes' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['room_number' => '201', 'room_type_id' => $roomTypeIds['Double'] ?? null, 'floor_id' => $floorIds['2'] ?? null, 'status' => 'reserved', 'notes' => 'Quiet side', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['room_number' => '202', 'room_type_id' => $roomTypeIds['Deluxe'] ?? null, 'floor_id' => $floorIds['2'] ?? null, 'status' => 'clean', 'notes' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['room_number' => '203', 'room_type_id' => $roomTypeIds['Deluxe'] ?? null, 'floor_id' => $floorIds['2'] ?? null, 'status' => 'maintenance', 'notes' => 'AC servicing pending', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['room_number' => '301', 'room_type_id' => $roomTypeIds['Suite'] ?? null, 'floor_id' => $floorIds['3'] ?? null, 'status' => 'occupied', 'notes' => 'Sea-facing suite', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['room_number' => '302', 'room_type_id' => $roomTypeIds['Suite'] ?? null, 'floor_id' => $floorIds['3'] ?? null, 'status' => 'available', 'notes' => null, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ];

        $rooms = array_values(array_filter($rooms, fn ($room) => $room['room_type_id'] && $room['floor_id']));

        DB::table('rooms')->upsert(
            $rooms,
            ['room_number'],
            ['room_type_id', 'floor_id', 'status', 'notes', 'is_active', 'updated_at']
        );

        $amenityIds = DB::table('amenities')->pluck('id', 'name');
        $roomIds = DB::table('rooms')->pluck('id', 'room_number');

        $pivotRows = [
            ['room' => '101', 'amenity' => 'WiFi'],
            ['room' => '101', 'amenity' => 'Air Conditioning'],
            ['room' => '101', 'amenity' => 'Smart TV'],
            ['room' => '102', 'amenity' => 'WiFi'],
            ['room' => '102', 'amenity' => 'Work Desk'],
            ['room' => '103', 'amenity' => 'WiFi'],
            ['room' => '103', 'amenity' => 'Smart TV'],
            ['room' => '201', 'amenity' => 'WiFi'],
            ['room' => '201', 'amenity' => 'Mini Bar'],
            ['room' => '202', 'amenity' => 'WiFi'],
            ['room' => '202', 'amenity' => 'Ocean View'],
            ['room' => '203', 'amenity' => 'WiFi'],
            ['room' => '301', 'amenity' => 'WiFi'],
            ['room' => '301', 'amenity' => 'Mini Bar'],
            ['room' => '301', 'amenity' => 'Ocean View'],
            ['room' => '302', 'amenity' => 'WiFi'],
            ['room' => '302', 'amenity' => 'Air Conditioning'],
            ['room' => '302', 'amenity' => 'Smart TV'],
        ];

        $amenityRoomRows = [];
        foreach ($pivotRows as $row) {
            $roomId = $roomIds[$row['room']] ?? null;
            $amenityId = $amenityIds[$row['amenity']] ?? null;

            if (!$roomId || !$amenityId) {
                continue;
            }

            $amenityRoomRows[] = [
                'room_id' => $roomId,
                'amenity_id' => $amenityId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($amenityRoomRows)) {
            DB::table('amenity_room')->upsert(
                $amenityRoomRows,
                ['amenity_id', 'room_id'],
                ['updated_at']
            );
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExtraServiceSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $services = [
            ['name' => 'Late Checkout', 'price' => 20, 'price_type' => 'per_stay', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Breakfast Buffet', 'price' => 12, 'price_type' => 'per_person', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Airport Pickup', 'price' => 35, 'price_type' => 'fixed', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Extra Bed', 'price' => 18, 'price_type' => 'per_night', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Laundry Service', 'price' => 8, 'price_type' => 'fixed', 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('extra_services')->upsert($services, ['name'], ['price', 'price_type', 'is_active', 'updated_at']);
    }
}

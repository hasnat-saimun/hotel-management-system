<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $settings = [
            ['key' => 'hotel_name', 'value' => 'Copilot Grand Hotel', 'group' => 'general', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'hotel_email', 'value' => 'reservations@copilotgrand.com', 'group' => 'general', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'hotel_phone', 'value' => '+1-555-100-2000', 'group' => 'general', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'check_in_time', 'value' => '14:00', 'group' => 'policy', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'check_out_time', 'value' => '12:00', 'group' => 'policy', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'default_tax_percent', 'value' => '10', 'group' => 'billing', 'created_at' => $now, 'updated_at' => $now],
            ['key' => 'currency', 'value' => 'USD', 'group' => 'billing', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('settings')->upsert($settings, ['key'], ['value', 'group', 'updated_at']);
    }
}

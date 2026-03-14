<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            StaffUserSeeder::class,
            FloorSeeder::class,
            RoomTypeSeeder::class,
            AmenitySeeder::class,
            RoomSeeder::class,
            GuestSeeder::class,
            GuestDocumentSeeder::class,
            ReservationSeeder::class,
            ExtraServiceSeeder::class,
            ReservationServiceSeeder::class,
            InvoiceSeeder::class,
            InvoiceItemSeeder::class,
            PaymentSeeder::class,
            RefundSeeder::class,
            HousekeepingTaskSeeder::class,
            MaintenanceTicketSeeder::class,
            SettingSeeder::class,
            ActivityLogSeeder::class,
        ]);
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reservations') || !Schema::hasColumn('reservations', 'status')) {
            return;
        }

        // Normalize existing data to the new allowed set.
        DB::table('reservations')->where('status', 'pending')->update(['status' => 'booked']);

        DB::table('reservations')
            ->whereIn('status', ['checked_in', 'checked-in', 'checkedin', 'checked_out', 'checked-out', 'checkedout'])
            ->update(['status' => 'confirmed']);

        DB::table('reservations')
            ->whereNotIn('status', ['booked', 'confirmed', 'cancelled', 'no_show'])
            ->update(['status' => 'booked']);

        $driver = Schema::getConnection()->getDriverName();

        // Enforce the enum on MySQL/MariaDB (common on XAMPP).
        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement(
                "ALTER TABLE `reservations` MODIFY `status` ENUM('booked','confirmed','cancelled','no_show') NOT NULL DEFAULT 'booked'"
            );
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('reservations') || !Schema::hasColumn('reservations', 'status')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        // Re-expand the enum to the historical set used by this project.
        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement(
                "ALTER TABLE `reservations` MODIFY `status` ENUM('pending','confirmed','checked_in','checked_out','cancelled','no_show','booked') NOT NULL DEFAULT 'booked'"
            );
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('stays') || !Schema::hasColumn('stays', 'status')) {
            return;
        }

        // Normalize historical values (if this migration runs after an earlier enum definition).
        DB::table('stays')->where('status', 'checked_in')->update(['status' => 'in_house']);
        DB::table('stays')->where('status', 'checked_out')->update(['status' => 'checked_out']);

        DB::table('stays')
            ->whereNotIn('status', ['in_house', 'checked_out', 'moved', 'no_show'])
            ->update(['status' => 'in_house']);

        $driver = Schema::getConnection()->getDriverName();

        // Enforce the enum on MySQL/MariaDB.
        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement(
                "ALTER TABLE `stays` MODIFY `status` ENUM('in_house','checked_out','moved','no_show') NOT NULL DEFAULT 'in_house'"
            );
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('stays') || !Schema::hasColumn('stays', 'status')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        // Revert to the earlier checked_in/checked_out enum.
        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::table('stays')->where('status', 'in_house')->update(['status' => 'checked_in']);
            DB::table('stays')->where('status', 'checked_out')->update(['status' => 'checked_out']);
            DB::table('stays')->where('status', 'moved')->update(['status' => 'checked_in']);
            DB::table('stays')->where('status', 'no_show')->update(['status' => 'checked_in']);

            DB::statement(
                "ALTER TABLE `stays` MODIFY `status` ENUM('checked_in','checked_out') NOT NULL DEFAULT 'checked_in'"
            );
        }
    }
};

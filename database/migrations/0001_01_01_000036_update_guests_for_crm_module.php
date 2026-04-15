<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Make guests.email nullable (keeps unique index; MySQL allows multiple NULLs).
        // Use raw SQL to avoid doctrine/dbal dependency.
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE `guests` MODIFY `email` VARCHAR(255) NULL");
        } elseif ($driver === 'sqlite') {
            // SQLite doesn't support MODIFY COLUMN easily; keep existing definition in sqlite.
            // This project uses MySQL in production (XAMPP). If you use sqlite for tests,
            // consider adjusting the original migration instead.
        }

        Schema::table('guests', function (Blueprint $table) {
            if (!Schema::hasColumn('guests', 'company_id')) {
                $table->foreignId('company_id')->nullable()->after('gender')->constrained('companies')->nullOnDelete();
            }
            if (!Schema::hasColumn('guests', 'travel_agent_id')) {
                $table->foreignId('travel_agent_id')->nullable()->after('company_id')->constrained('travel_agents')->nullOnDelete();
            }
            if (!Schema::hasColumn('guests', 'loyalty_id')) {
                $table->foreignId('loyalty_id')->nullable()->after('travel_agent_id')->constrained('loyalties')->nullOnDelete();
            }

            if (!Schema::hasColumn('guests', 'deleted_at')) {
                $table->softDeletes();
            }

            // Requirements: phone indexed
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            if (Schema::hasColumn('guests', 'company_id')) {
                $table->dropConstrainedForeignId('company_id');
            }
            if (Schema::hasColumn('guests', 'travel_agent_id')) {
                $table->dropConstrainedForeignId('travel_agent_id');
            }
            if (Schema::hasColumn('guests', 'loyalty_id')) {
                $table->dropConstrainedForeignId('loyalty_id');
            }

            if (Schema::hasColumn('guests', 'deleted_at')) {
                $table->dropSoftDeletes();
            }

            // Index removal: Laravel generated name is predictable but differs per DB.
            // We attempt the default name.
            try {
                $table->dropIndex(['phone']);
            } catch (\Throwable $e) {
                // ignore
            }
        });

        // Revert nullable email if needed (mysql only)
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE `guests` MODIFY `email` VARCHAR(255) NOT NULL");
        }
    }
};

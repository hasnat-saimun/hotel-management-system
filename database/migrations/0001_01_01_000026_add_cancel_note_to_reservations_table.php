<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reservations')) {
            return;
        }

        if (Schema::hasColumn('reservations', 'cancel_note')) {
            return;
        }

        Schema::table('reservations', function (Blueprint $table) {
            $table->text('cancel_note')->nullable();
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('reservations')) {
            return;
        }

        if (!Schema::hasColumn('reservations', 'cancel_note')) {
            return;
        }

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('cancel_note');
        });
    }
};

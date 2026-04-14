<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('room_block_rooms', function (Blueprint $table) {
            $table->foreignId('reservation_id')
                ->nullable()
                ->after('assigned_guest_id')
                ->constrained('reservations')
                ->nullOnDelete();

            $table->unique(['reservation_id']);
            $table->index(['room_block_id', 'reservation_id']);
        });
    }

    public function down(): void
    {
        Schema::table('room_block_rooms', function (Blueprint $table) {
            $table->dropUnique(['reservation_id']);
            $table->dropIndex(['room_block_id', 'reservation_id']);
            $table->dropConstrainedForeignId('reservation_id');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->foreignId('room_block_id')->nullable()->after('guest_id')->constrained('room_blocks')->nullOnDelete();
            $table->index('room_block_id');
        });

        Schema::create('reservation_guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->cascadeOnDelete();
            $table->foreignId('guest_id')->constrained('guests')->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->unique(['reservation_id', 'guest_id']);
            $table->index(['reservation_id', 'is_primary']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_guests');

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('room_block_id');
        });
    }
};

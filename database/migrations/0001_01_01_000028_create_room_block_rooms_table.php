<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_block_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_block_id')->constrained('room_blocks')->cascadeOnDelete();
            $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->foreignId('room_type_id')->nullable()->constrained('room_types')->nullOnDelete();

            // Guest assignment planning at block level (optional)
            $table->foreignId('assigned_guest_id')->nullable()->constrained('guests')->nullOnDelete();

            $table->enum('status', ['blocked', 'converted', 'released'])->default('blocked');
            $table->timestamps();

            $table->index(['room_block_id', 'status']);
            $table->index(['room_id']);
            $table->index(['room_type_id']);
            $table->unique(['room_block_id', 'room_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_block_rooms');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_blocks', function (Blueprint $table) {
            $table->id();

            $table->string('group_name');
            $table->date('start_date');
            $table->date('end_date');

            $table->unsignedInteger('total_rooms')->default(0);
            $table->enum('status', ['tentative', 'confirmed', 'cancelled'])->default('tentative');

            // Optional inventory release deadline (auto-expire)
            $table->dateTime('release_at')->nullable();
            $table->dateTime('released_at')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['start_date', 'end_date']);
            $table->index(['status', 'release_at', 'released_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_blocks');
    }
};

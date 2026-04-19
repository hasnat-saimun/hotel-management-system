<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stays', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reservation_id')->constrained('reservations')->cascadeOnDelete();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();

            $table->dateTime('check_in_time');
            $table->dateTime('check_out_time')->nullable();

            $table->enum('status', ['in_house', 'checked_out', 'moved', 'no_show'])->default('in_house');

            $table->unsignedInteger('adults')->default(1);
            $table->unsignedInteger('children')->default(0);

            $table->timestamps();

            $table->index(['reservation_id', 'room_id']);
            $table->index('status');
            $table->index('check_in_time');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stays');
    }
};

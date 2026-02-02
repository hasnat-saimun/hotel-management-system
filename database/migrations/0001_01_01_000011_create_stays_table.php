<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->cascadeOnDelete();
            $table->datetime('check_in_at')->nullable();
            $table->datetime('check_out_at')->nullable();
            $table->foreignId('cancelled_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('cancelled_at')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['in_house', 'checked_out'])->default('in_house');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stays');
    }
};

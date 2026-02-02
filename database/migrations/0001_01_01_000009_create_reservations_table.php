<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('reservation_code')->unique();
            $table->foreignId('guest_id')->constrained('guests')->cascadeOnDelete();
            $table->enum('source', ['walkin', 'phone', 'website', 'agent', 'ota'])->nullable();
            $table->enum('status', ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled', 'no_show','booked'])->default('booked');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->integer('adults')->default();
            $table->integer('children')->default(0);
            $table->text('special_requests')->nullable();
            $table->foreignId('cancelled_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('user_cancelled_at')->constrained('users')->cascadeOnDelete();
            $table->datetime('cancelled_at')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->index('check_in_date')->lock('shared');
            $table->index('check_out_date')->lock('shared');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};

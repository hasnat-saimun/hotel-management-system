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
            // Guest details (kept directly on reservation for simplicity)
            $table->foreignId('guest_id')->nullable()->constrained('guests')->nullOnDelete();

            $table->string('reservation_code')->nullable()->unique();
            $table->string('channel')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled', 'no_show', 'booked'])->default('booked');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid', 'refunded'])->default('unpaid');

            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->unsignedInteger('adults')->default(1);
            $table->unsignedInteger('children')->default(0);

            $table->decimal('rate', 10, 2)->default(0);
            $table->json('extras')->nullable();

            $table->text('note')->nullable();
            $table->timestamps();

            $table->index('check_in_date');
            $table->index('check_out_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('folios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->cascadeOnDelete();
            $table->foreignId('guest_id')->nullable()->constrained('guests')->nullOnDelete();

            $table->decimal('room_charge_total', 10, 2)->default(0);
            $table->decimal('discount_total', 10, 2)->default(0);
            $table->decimal('tax_total', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);

            $table->enum('status', ['open', 'closed', 'void'])->default('open');
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique('reservation_id');
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('folios');
    }
};

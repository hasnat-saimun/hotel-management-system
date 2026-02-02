<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->cascadeOnDelete();
            $table->foreignId('paid_by_guest_id')->constrained('guests')->cascadeOnDelete();
            $table->decimal('amount', 10, 2)->default(0);
            $table->enum('method', ['cash', 'credit_card', 'debit_card', 'bank_transfer', 'other'])->nullable();
            $table->date('paid_at');
            $table->foreignId('received_by')->constrained('users')->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};

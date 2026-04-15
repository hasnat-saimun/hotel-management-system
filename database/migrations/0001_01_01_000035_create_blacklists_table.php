<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blacklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->constrained('guests')->cascadeOnDelete();
            $table->text('reason');
            $table->date('blocked_until')->nullable();
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();

            $table->unique('guest_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blacklists');
    }
};

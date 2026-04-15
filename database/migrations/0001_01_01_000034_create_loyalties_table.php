<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalties', function (Blueprint $table) {
            $table->id();
            $table->string('level_name');
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->unsignedInteger('points_required')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->unique('level_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalties');
    }
};

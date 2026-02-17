<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('amenity_room', function (Blueprint $table) {
            $table->id();
            $table->foreignId('amenity_id')->constrained('amenities')->cascadeOnDelete();
            // rooms table is created in a later migration (000006), so we add the FK in a follow-up migration
            $table->unsignedBigInteger('room_id');
            $table->timestamps();

            $table->unique(['amenity_id', 'room_id']);
            $table->index('room_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('amenity_room');
    }
};

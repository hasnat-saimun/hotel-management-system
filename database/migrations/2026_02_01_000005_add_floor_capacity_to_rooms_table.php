<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (!Schema::hasColumn('rooms', 'floor')) {
                $table->string('floor')->nullable()->after('type');
            }
            if (!Schema::hasColumn('rooms', 'capacity')) {
                $table->integer('capacity')->default(1)->after('floor');
            }
        });
    }

    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (Schema::hasColumn('rooms', 'capacity')) {
                $table->dropColumn('capacity');
            }
            if (Schema::hasColumn('rooms', 'floor')) {
                $table->dropColumn('floor');
            }
        });
    }
};

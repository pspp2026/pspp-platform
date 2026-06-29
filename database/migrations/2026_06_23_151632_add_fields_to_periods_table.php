<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('periods', function (Blueprint $table) {

            $table->string('name')->after('id');

            $table->time('start_time')->after('name');

            $table->time('end_time')->after('start_time');

        });
    }

    public function down(): void
    {
        Schema::table('periods', function (Blueprint $table) {

            $table->dropColumn([
                'name',
                'start_time',
                'end_time'
            ]);

        });
    }
};
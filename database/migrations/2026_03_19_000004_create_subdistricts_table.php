<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subdistricts', function (Blueprint $table) {
            $table->id();

            // 🔥 รหัสตำบล (ใช้เชื่อม FK)
            $table->unsignedBigInteger('subdistrict_id')->unique();

            // 🔥 ชื่อ
            $table->string('name_th');
            $table->string('name_en')->nullable();

            // 🔥 เชื่อมอำเภอ
            $table->unsignedBigInteger('district_id');

            // 🔗 Foreign Key
            $table->foreign('district_id')
                  ->references('district_id')
                  ->on('districts')
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subdistricts');
    }
};
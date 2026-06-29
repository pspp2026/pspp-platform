<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('provinces', function (Blueprint $table) {

            // 🔑 ใช้ province_id แทน id
            $table->unsignedInteger('province_id')->primary();

            // 🇹🇭 ชื่อไทย
            $table->string('name_th', 120)->nullable();

            // 🇬🇧 ชื่ออังกฤษ
            $table->string('name_en', 120)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provinces');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subject_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ชื่อกลุ่ม
            $table->string('type')->nullable(); // พื้นฐาน / เพิ่มเติม / กิจกรรม
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_groups');
    }
};
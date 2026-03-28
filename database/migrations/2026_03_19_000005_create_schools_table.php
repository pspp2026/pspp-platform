<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();

            // 🔑 รหัสโรงเรียน
            $table->string('school_code')->unique();

            // 🏫 ชื่อโรงเรียน
            $table->string('school_name');

            // 🛕 วัด
            $table->string('temple')->nullable();

            // 📍 ที่อยู่
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();

            // 📌 ความสัมพันธ์
            $table->foreignId('subdistrict_id')
                  ->nullable()
                  ->constrained('subdistricts')
                  ->nullOnDelete();

            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedInteger('province_id')->nullable();
            $table->unsignedTinyInteger('zone_code')->nullable()->index();

            // 🌐 ข้อมูลติดต่อ
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            // 📊 สถานะ
            $table->enum('status', ['draft', 'complete'])->default('draft');

            $table->timestamps();

            // ⚡ index
            $table->index('district_id');
            $table->index('province_id');
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropForeign(['subdistrict_id']);
        });

        Schema::dropIfExists('schools');
    }
};
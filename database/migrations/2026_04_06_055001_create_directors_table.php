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
        Schema::create('directors', function (Blueprint $table) {
            $table->id();

            // 🔗 เชื่อม users (login)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // 🔑 รหัสผู้บริหาร
            $table->string('director_code')->unique();

            // 👤 ข้อมูลส่วนตัว
            $table->string('prefix')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();

            // 🏫 โรงเรียนที่ดูแล
            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            // 🏢 ตำแหน่ง (เช่น ผอ., รอง ผอ.)
            $table->string('position')->nullable();

            // 📌 สถานะ
            $table->string('status')->default('active');

            $table->timestamps();

            // 🔥 index เพิ่มความเร็ว
            $table->index(['user_id']);
            $table->index(['school_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('directors');
    }
};
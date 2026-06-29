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
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();

            // 🔗 เชื่อม users
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // 🔑 รหัสบุคลากร
            $table->string('staff_code')->unique();

            // 👤 ข้อมูลส่วนตัว
            $table->string('prefix')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();

            // 🏢 ข้อมูลตำแหน่ง
            $table->string('position')->nullable();
            $table->string('department')->nullable();

            // 🏫 โรงเรียน
            $table->foreignId('school_id')
                ->nullable()
                ->constrained('schools')
                ->nullOnDelete();

            // 🛕 วัด (กรณีพระ)
            $table->foreignId('temple_id')
                ->nullable()
                ->constrained('temples')
                ->nullOnDelete();

            // 🧘 พระหรือไม่
            $table->boolean('is_monk')->default(false);

            // 📌 สถานะ
            $table->string('status')->default('active');

            $table->timestamps();

            // 🔥 index เพิ่มความเร็ว
            $table->index(['user_id']);
            $table->index(['school_id']);
            $table->index(['temple_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staffs');
    }
};
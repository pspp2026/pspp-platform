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
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();

            $table->foreignId('attendance_session_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('status', [
                'present',
                'late',
                'leave',
                'absent'
            ])->default('present');

            $table->string('remark')->nullable();

            $table->timestamp('recorded_at')->nullable();

            $table->timestamps();

            // นักเรียน 1 คน เช็กชื่อได้ครั้งเดียวต่อ Session
            $table->unique([
                'attendance_session_id',
                'student_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
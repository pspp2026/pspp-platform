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
        Schema::create('attendances', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | ความสัมพันธ์
            |--------------------------------------------------------------------------
            */

            $table->foreignId('attendance_session_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | สถานะการเข้าเรียน
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'present',
                'late',
                'leave',
                'absent',
            ])->default('present');

            /*
            |--------------------------------------------------------------------------
            | เวลาเข้าเรียน
            |--------------------------------------------------------------------------
            */

            $table->time('check_in_time')->nullable();

            /*
            |--------------------------------------------------------------------------
            | หมายเหตุ
            |--------------------------------------------------------------------------
            */

            $table->string('remark')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | ป้องกันข้อมูลซ้ำ
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'attendance_session_id',
                'student_id',
            ], 'attendance_student_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
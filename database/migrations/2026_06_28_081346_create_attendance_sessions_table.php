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
        Schema::create('attendance_sessions', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | ความสัมพันธ์
            |--------------------------------------------------------------------------
            */

            $table->foreignId('school_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('academic_term_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('schedule_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('classroom_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('subject_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('teacher_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('period_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | ข้อมูลการเช็กชื่อ
            |--------------------------------------------------------------------------
            */

            $table->date('attendance_date');

            $table->string('topic')->nullable();

            $table->text('note')->nullable();

            $table->enum('status', [
                'draft',
                'completed',
            ])->default('draft');

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | ป้องกันการสร้าง Session ซ้ำ
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'schedule_id',
                'attendance_date',
                'period_id',
            ], 'attendance_session_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_sessions');
    }
};
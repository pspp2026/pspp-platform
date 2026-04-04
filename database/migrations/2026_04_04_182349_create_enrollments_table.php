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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();

            // 🔗 ความสัมพันธ์
            $table->foreignId('student_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('school_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // 🎓 ข้อมูลการศึกษา
            $table->string('grade_level');      // เช่น ม.1
            $table->integer('semester');        // 1 หรือ 2
            $table->integer('academic_year');   // เช่น 2568

            $table->timestamps();

            // 🔥 ป้องกันข้อมูลซ้ำ (สำคัญมาก)
            $table->unique(['student_id', 'semester', 'academic_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
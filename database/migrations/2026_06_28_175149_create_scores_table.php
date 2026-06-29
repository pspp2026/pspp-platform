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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();

            // ตารางสอน
            $table->foreignId('schedule_id')
                ->constrained()
                ->cascadeOnDelete();

            // นักเรียน
            $table->foreignId('student_id')
                ->constrained()
                ->cascadeOnDelete();

            // คะแนน
            $table->decimal('work_score', 5, 2)->default(0);
            $table->decimal('midterm_score', 5, 2)->default(0);
            $table->decimal('final_score', 5, 2)->default(0);

            // คะแนนเสริม
            $table->decimal('attendance_score', 5, 2)->default(0);
            $table->decimal('behavior_score', 5, 2)->default(0);

            // โบนัส / หักคะแนน
            $table->decimal('extra_score', 5, 2)->default(0);
            $table->decimal('deduction_score', 5, 2)->default(0);

            // คะแนนรวม
            $table->decimal('total_score', 5, 2)->default(0);

            // หมายเหตุ
            $table->text('remark')->nullable();

            $table->timestamps();

            // นักเรียน 1 คน มีคะแนนได้เพียง 1 ชุด ต่อ 1 ตารางสอน
            $table->unique([
                'schedule_id',
                'student_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
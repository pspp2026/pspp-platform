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
        Schema::create('grades', function (Blueprint $table) {

            $table->id();

            // อ้างอิงคะแนน
            $table->foreignId('score_id')
                ->constrained()
                ->cascadeOnDelete();

            // เกรด
            $table->string('grade', 10);

            // ค่าระดับคะแนน
            $table->decimal('grade_point', 3, 2);

            // ผ่าน / ไม่ผ่าน
            $table->boolean('passed')->default(true);

            // วันที่คำนวณเกรด
            $table->timestamp('calculated_at')->nullable();

            // หมายเหตุ
            $table->text('remark')->nullable();

            $table->timestamps();

            // คะแนน 1 รายการ มีเกรดได้เพียง 1 รายการ
            $table->unique('score_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
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
        Schema::create('pspp_evaluations', function (Blueprint $table) {

            $table->id();

            // ผู้ตอบ
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Snapshot ข้อมูลผู้ตอบ
            $table->unsignedBigInteger('school_id')->nullable();
            $table->string('school_name')->nullable();
            $table->string('role');
            $table->string('class_level')->nullable();
            $table->string('student_code')->nullable();

            // คะแนน 23 ข้อ
            for ($i = 1; $i <= 23; $i++) {
                $table->tinyInteger("answer{$i}");
            }

            // ข้อเสนอแนะ
            $table->text('suggestion')->nullable();

            // วันเวลาที่ตอบ
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pspp_evaluations');
    }
};
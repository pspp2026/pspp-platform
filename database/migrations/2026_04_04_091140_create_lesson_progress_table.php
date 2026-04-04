<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonProgressTable extends Migration
{
    public function up()
    {
        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();

            // ความสัมพันธ์
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('lesson_id');

            // Activity Tracking
            $table->boolean('read_done')->default(false);
            $table->timestamp('read_at')->nullable();

            $table->boolean('quiz_done')->default(false);
            $table->integer('quiz_score')->nullable();
            $table->timestamp('quiz_at')->nullable();

            $table->boolean('reflection_done')->default(false);
            $table->timestamp('reflection_at')->nullable();

            // เวลาใช้งาน
            $table->integer('time_spent')->default(0); // วินาที

            // ความก้าวหน้า
            $table->integer('progress_percent')->default(0);

            // ผลลัพธ์สุดท้าย
            $table->boolean('completed')->default(false);
            $table->timestamp('completed_at')->nullable();

            // timestamps
            $table->timestamps();

            // ป้องกันข้อมูลซ้ำ
            $table->unique(['user_id', 'lesson_id']);

            // เพิ่มความเร็ว query
            $table->index('user_id');
            $table->index('lesson_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lesson_progress');
    }
}
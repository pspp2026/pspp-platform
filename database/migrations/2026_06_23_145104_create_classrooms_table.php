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
        Schema::create('classrooms', function (Blueprint $table) {

            $table->id();

            // โรงเรียน
            $table->foreignId('school_id')
                ->constrained()
                ->cascadeOnDelete();


            // เช่น ม.1/1
            $table->string('name');


            // ระดับชั้น
            $table->string('level')
                ->nullable();


            // จำนวนเด็ก
            $table->integer('student_count')
                ->default(0);


            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};

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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();

            $table->string('subject_code')->unique(); // รหัสวิชา
            $table->string('subject_name'); // ชื่อวิชา

            $table->integer('hours')->nullable(); // ชั่วโมงเรียน
            $table->decimal('credits', 4, 1)->nullable(); // หน่วยกิต

            $table->foreignId('group_id')
                  ->constrained('subject_groups')
                  ->cascadeOnDelete();

            $table->string('level')->nullable(); // ม.1 - ม.6
            $table->string('type')->nullable();  // สามัญ / บาลี / กิจกรรม

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
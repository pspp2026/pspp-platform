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
        Schema::table('classrooms', function (Blueprint $table) {

            $table->foreignId('teacher_id')
                ->nullable()
                ->after('school_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->string('academic_year')
                ->nullable()
                ->after('level');

            $table->boolean('status')
                ->default(true)
                ->after('student_count');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn(['teacher_id', 'academic_year', 'status']);
        });
    }
};

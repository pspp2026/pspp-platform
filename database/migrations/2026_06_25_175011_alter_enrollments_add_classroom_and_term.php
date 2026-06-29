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
        Schema::table('enrollments', function (Blueprint $table) {

            // ห้องเรียน
            $table->foreignId('classroom_id')
                ->nullable()
                ->after('school_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete();

            // ภาคเรียน
            $table->foreignId('academic_term_id')
                ->nullable()
                ->after('classroom_id')
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete();

            // สถานะ
            $table->string('status')
                ->default('active')
                ->after('academic_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {

            $table->dropForeign(['classroom_id']);
            $table->dropForeign(['academic_term_id']);

            $table->dropColumn([
                'classroom_id',
                'academic_term_id',
                'status'
            ]);
        });
    }
};
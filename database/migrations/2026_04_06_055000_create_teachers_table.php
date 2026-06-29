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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('teacher_code')->unique();

            $table->string('prefix')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();

            $table->string('position')->nullable();
            $table->string('department')->nullable();
            $table->string('subject')->nullable();

            $table->unsignedBigInteger('school_id')->nullable();
            $table->foreignId('temple_id')->nullable()->constrained()->nullOnDelete();

            $table->boolean('is_monk')->default(false);

            $table->date('hire_date')->nullable();
            $table->string('status')->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
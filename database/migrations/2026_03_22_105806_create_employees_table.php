<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // FK
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();

            // ข้อมูลหลัก
            $table->string('employee_code')->unique()->index();

            $table->string('name_th')->index();
            $table->string('name_en')->nullable();
            $table->string('id_card', 20)->nullable()->unique();

            $table->string('phone', 20)->nullable();
            $table->string('photo')->nullable();

            $table->string('position')->nullable()->index();
            $table->string('department')->nullable()->index();

            $table->date('hire_date')->nullable();

            $table->enum('status', ['active', 'inactive', 'resigned'])
                  ->default('active')->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
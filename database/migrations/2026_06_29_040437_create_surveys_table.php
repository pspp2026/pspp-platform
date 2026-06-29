<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surveys', function (Blueprint $table) {

            $table->id();

            $table->foreignId('school_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->string('title');

            $table->text('description')->nullable();

            $table->text('objective')->nullable();

            $table->enum('target_type', [
                'all',
                'teacher',
                'student',
                'staff',
                'parent'
            ])->default('all');

            $table->enum('status', [
                'draft',
                'published',
                'closed'
            ])->default('draft');

            $table->boolean('is_public')->default(false);

            $table->timestamp('start_at')->nullable();

            $table->timestamp('end_at')->nullable();

            $table->foreignId('created_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
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
        Schema::create('survey_questions', function (Blueprint $table) {

            $table->id();

            $table->foreignId('section_id')
                ->constrained('survey_sections')
                ->cascadeOnDelete();

            $table->text('question');

            $table->text('description')->nullable();

            $table->string('question_type');

            $table->boolean('required')->default(true);

            $table->integer('sort_order')->default(1);

            $table->json('settings')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_questions');
    }
};

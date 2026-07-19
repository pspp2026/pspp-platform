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
        Schema::create('user_login_logs', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->foreignId('school_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('role');

            $table->timestamp('login_at');

            $table->timestamp('logout_at')->nullable();

            $table->string('ip_address',45)->nullable();

            $table->text('user_agent')->nullable();

            $table->string('session_id')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_login_logs');
    }
};

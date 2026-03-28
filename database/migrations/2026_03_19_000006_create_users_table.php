<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('user_code')->nullable()->unique();
            $table->string('external_code')->nullable();
            $table->string('name');
            $table->string('email')->unique();

            $table->string('address1')->nullable();
            $table->string('address2')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            $table->timestamps();

            $table->string('status')->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->string('role')->default('teacher');

            $table->unsignedBigInteger('school_id')->nullable();
            $table->unsignedBigInteger('province_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedBigInteger('subdistrict_id')->nullable();

            $table->string('phone')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('id_card')->nullable();

            $table->string('name_th')->nullable();
            $table->string('name_en')->nullable();

            // foreign key
            $table->foreign('school_id')
                  ->references('id')
                  ->on('schools')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
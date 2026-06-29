<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // 🔑 รหัส
            $table->string('user_code')->unique()->nullable();
            $table->string('external_code')->nullable();

            // 🔐 login
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // 👤 ข้อมูลส่วนตัว
            $table->string('id_card')->nullable();
            $table->string('name_th')->nullable();
            $table->string('name_en')->nullable();
            $table->string('phone')->nullable();
            $table->string('profile_image')->nullable();

            // 🏫 โรงเรียน
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();


            // 📍 ที่อยู่
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->foreignId('province_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('subdistrict_id')->nullable()->constrained()->nullOnDelete();
     

            // 🔐 ระบบสิทธิ์
            $table->string('status')->default('pending');
            $table->string('role')->default('user');

            // 👨‍💼 ผู้อนุมัติ
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
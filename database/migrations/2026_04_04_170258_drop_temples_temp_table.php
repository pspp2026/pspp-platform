<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('temples_temp');
    }

    public function down(): void
    {
        // ถ้าจะ rollback (ไม่จำเป็นก็ได้)
        Schema::create('temples_temp', function ($table) {
            $table->id();
            $table->string('temple_name')->nullable();
            $table->text('address1')->nullable();
            $table->text('address2')->nullable();
            $table->string('province_name')->nullable();
            $table->string('district_name')->nullable();
            $table->string('subdistrict_name')->nullable();
            $table->timestamps();
        });
    }
};
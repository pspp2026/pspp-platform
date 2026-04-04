<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('temples', function (Blueprint $table) {

            if (!Schema::hasColumn('temples', 'order_no')) {
                $table->integer('order_no')->nullable();
            }

            if (!Schema::hasColumn('temples', 'temple_code')) {
                $table->string('temple_code')->nullable();
            }

            if (!Schema::hasColumn('temples', 'temple_name')) {
                $table->string('temple_name')->nullable();
            }

            if (!Schema::hasColumn('temples', 'temple_type')) {
                $table->string('temple_type')->nullable();
            }

            if (!Schema::hasColumn('temples', 'sect')) {
                $table->string('sect')->nullable();
            }

            if (!Schema::hasColumn('temples', 'registration_type')) {
                $table->string('registration_type')->nullable();
            }

            if (!Schema::hasColumn('temples', 'established_date')) {
                $table->string('established_date')->nullable();
            }

            if (!Schema::hasColumn('temples', 'has_visung')) {
                $table->string('has_visung')->nullable();
            }

            if (!Schema::hasColumn('temples', 'visung_date')) {
                $table->string('visung_date')->nullable();
            }

            if (!Schema::hasColumn('temples', 'status_date')) {
                $table->string('status_date')->nullable();
            }

            if (!Schema::hasColumn('temples', 'subdistrict')) {
                $table->string('subdistrict')->nullable();
            }

            if (!Schema::hasColumn('temples', 'district')) {
                $table->string('district')->nullable();
            }

            if (!Schema::hasColumn('temples', 'province')) {
                $table->string('province')->nullable();
            }

            if (!Schema::hasColumn('temples', 'postal_code')) {
                $table->string('postal_code')->nullable();
            }

            if (!Schema::hasColumn('temples', 'phone')) {
                $table->string('phone')->nullable();
            }

            if (!Schema::hasColumn('temples', 'fax')) {
                $table->string('fax')->nullable();
            }

            if (!Schema::hasColumn('temples', 'email')) {
                $table->string('email')->nullable();
            }

            if (!Schema::hasColumn('temples', 'website')) {
                $table->string('website')->nullable();
            }

            if (!Schema::hasColumn('temples', 'village')) {
                $table->string('village')->nullable();
            }

            if (!Schema::hasColumn('temples', 'house_no')) {
                $table->string('house_no')->nullable();
            }

            if (!Schema::hasColumn('temples', 'moo')) {
                $table->string('moo')->nullable();
            }

            if (!Schema::hasColumn('temples', 'soi')) {
                $table->string('soi')->nullable();
            }

            if (!Schema::hasColumn('temples', 'road')) {
                $table->string('road')->nullable();
            }

            if (!Schema::hasColumn('temples', 'note')) {
                $table->text('note')->nullable();
            }

        });

        // 🔥 เพิ่ม unique กันข้อมูลซ้ำ (optional แต่แนะนำ)
        // ต้องแน่ใจว่าไม่มีข้อมูลซ้ำก่อน
        try {
            DB::statement('CREATE UNIQUE INDEX temples_temple_code_unique ON temples (temple_code)');
        } catch (\Exception $e) {
            // ถ้ามีอยู่แล้ว จะไม่ error
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('temples', function (Blueprint $table) {

            $columns = [
                'order_no',
                'temple_code',
                'temple_name',
                'temple_type',
                'sect',
                'registration_type',
                'established_date',
                'has_visung',
                'visung_date',
                'status_date',
                'subdistrict',
                'district',
                'province',
                'postal_code',
                'phone',
                'fax',
                'email',
                'website',
                'village',
                'house_no',
                'moo',
                'soi',
                'road',
                'note'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('temples', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        // ลบ index ถ้ามี
        try {
            DB::statement('DROP INDEX temples_temple_code_unique ON temples');
        } catch (\Exception $e) {
            // ignore
        }
    }
};
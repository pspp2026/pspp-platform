<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('temples', function (Blueprint $table) {

            if (Schema::hasColumn('temples', 'address1')) {
                $table->dropColumn('address1');
            }

            if (Schema::hasColumn('temples', 'address2')) {
                $table->dropColumn('address2');
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('temples', function (Blueprint $table) {

            // เผื่อ rollback
            if (!Schema::hasColumn('temples', 'address1')) {
                $table->text('address1')->nullable();
            }

            if (!Schema::hasColumn('temples', 'address2')) {
                $table->text('address2')->nullable();
            }

        });
    }
};
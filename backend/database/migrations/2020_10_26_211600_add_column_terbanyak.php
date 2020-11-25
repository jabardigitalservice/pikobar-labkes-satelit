<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTerbanyak extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kota_terbanyak', function (Blueprint $table) {
            $table->string('tipe')->nullable();
        });
        Schema::table('fasyankes_terbanyak', function (Blueprint $table) {
            $table->string('tipe')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kota_terbanyak', function (Blueprint $table) {
            $table->dropColumn('tipe');
        });
        Schema::table('fasyankes_terbanyak', function (Blueprint $table) {
            $table->dropColumn('tipe');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnHasilPct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('register', function (Blueprint $table) {
            $table->string('hasil_rdt')->nullable();
        });

        Schema::table('pasien', function (Blueprint $table) {
            $table->integer('usia_tahun')->nullable();
            $table->integer('usia_bulan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('register', function (Blueprint $table) {
            $table->dropColumn('hasil_rdt');
        });
        Schema::table('pasien', function (Blueprint $table) {
            $table->dropColumn('usia_tahun');
            $table->dropColumn('usia_bulan');
        });
    }
}

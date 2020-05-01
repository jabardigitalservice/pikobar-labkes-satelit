<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRujukanRegister extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('register', function (Blueprint $table) {
            $table->string('kunjungan_ke')->nullable();
            $table->date('tanggal_kunjungan')->nullable();
            $table->string('rs_kunjungan')->nullable();
            $table->string('dinkes_pengirim')->nullable();
            $table->string('other_dinas_pengirim')->nullable();
            $table->string('nama_rs')->nullable();
            $table->string('other_nama_rs')->nullable();
            $table->string('fasyankes_pengirim')->nullable();
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
            $table->dropColumn('kunjungan_ke');
            $table->dropColumn('rs_kunjungan');
            $table->dropColumn('dinkes_pengirim');
            $table->dropColumn('other_dinas_pengirim');
            $table->dropColumn('nama_rs');
            $table->dropColumn('other_nama_rs');
            $table->dropColumn('fasyankes_pengirim');
            $table->dropColumn('tanggal_kunjungan');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVariabelDataSampel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->integer('jenis_identitas')->nullable();
            $table->text('alamat_domisili')->nullable();
            $table->integer('kode_kabupaten')->nullable();
            $table->string('nama_kabupaten')->nullable();
            $table->integer('kode_kecamatan')->nullable();
            $table->string('nama_kecamatan')->nullable();
            $table->integer('kode_kelurahan')->nullable();
            $table->string('nama_kelurahan')->nullable();
            $table->string('keterangan_warganegara')->nullable();
        });

        Schema::table('sampel', function (Blueprint $table) {
            $table->date('tanggal_gejala')->nullable();
        });

        Schema::table('fasyankes', function (Blueprint $table) {
            $table->string('kode_fasyankes')->nullable();
            $table->string('nama_fasyankes')->nullable();
            $table->string('jenis_fasyankes')->nullable();
            $table->string('kode_kabupaten_fasyankes')->nullable();
            $table->string('nama_kabupaten_fasyankes')->nullable();
        });
        Schema::table('lab_satelit', function (Blueprint $table) {
            $table->string('kode_lab')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->dropColumn('jenis_identitas');
            $table->dropColumn('alamat_domisili');
            $table->dropColumn('kode_kabupaten');
            $table->dropColumn('nama_kabupaten');
            $table->dropColumn('kode_kecamatan');
            $table->dropColumn('nama_kecamatan');
            $table->dropColumn('kode_kelurahan');
            $table->dropColumn('nama_kelurahan');
            $table->dropColumn('keterangan_warganegara');
        });

        Schema::table('sampel', function (Blueprint $table) {
            $table->dropColumn('tanggal_gejala');
        });

        Schema::table('fasyankes', function (Blueprint $table) {
            $table->dropColumn('kode_fasyankes');
            $table->dropColumn('nama_fasyankes');
            $table->dropColumn('kode_kabupaten');
            $table->dropColumn('nama_kabupaten');
        });
        Schema::table('lab_satelit', function (Blueprint $table) {
            $table->dropColumn('kode_lab');
        });
    }
}

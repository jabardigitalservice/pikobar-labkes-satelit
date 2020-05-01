<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register', function (Blueprint $table) {
            $table->id();
            $table->uuid('nomor_register')->unique();
            $table->unsignedInteger('fasyankes_id')->nullable();
            $table->string('nomor_rekam_medis')->nullable();
            $table->string('nama_dokter')->nullable();
            $table->string('no_telp', 30)->nullable();
            $table->string('sumber_pasien')->nullable();
            $table->string('jenis_registrasi')->nullable();
            $table->string('kunjungan_ke')->nullable();
            $table->string('dinkes_pengirim')->nullable();
            $table->string('other_dinas_pengirim')->nullable();
            $table->string('nama_rs')->nullable();
            $table->string('other_nama_rs')->nullable();
            $table->string('fasyankes_pengirim')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('register');
    }
}

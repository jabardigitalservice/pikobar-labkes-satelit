<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatKontakTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riwayat_kontak', function (Blueprint $table) {
            $table->unsignedInteger('register_id');
            $table->unsignedBigInteger('pasien_id');
            $table->string('nama_lengkap');
            $table->string('alamat');
            $table->string('hubungan');
            $table->date('tanggal_awal');
            $table->date('tanggal_akhir');
            $table->boolean('positif_covid19');
            $table->boolean('keluarga_sakit_sejenis');

            $table->timestamps();

            $table->foreign('register_id')->references('id')->on('register');
            $table->foreign('pasien_id')->references('id')->on('pasien');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('riwayat_kontak');
    }
}

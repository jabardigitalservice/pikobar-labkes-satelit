<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatPenyakitPenyertaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riwayat_penyakit_penyerta', function (Blueprint $table) {
            $table->unsignedInteger('register_id');
            $table->unsignedBigInteger('pasien_id');
            $table->json('daftar_penyakit'); // array json [{penyakit_penyerta_id, status(ya, tidak, tidak diisi)}]

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
        Schema::dropIfExists('riwayat_penyakit_penyerta');
    }
}

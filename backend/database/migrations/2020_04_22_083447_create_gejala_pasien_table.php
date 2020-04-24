<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGejalaPasienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gejala_pasien', function (Blueprint $table) {
            // $table->id();
            $table->unsignedInteger('register_id');
            $table->unsignedBigInteger('pasien_id');

            $table->boolean('pasien_rdt');
            $table->boolean('hasil_rdt_positif')->nullable(); // Reaktif | Non-reaktif
            $table->dateTime('tanggal_rdt')->nullable();
            $table->text('keterangan_rdt')->nullable();

            $table->dateTime('tanggal_onset_gejala');
            $table->json('daftar_gejala'); // [{gejala_id, status(ya, tidak, tidak_diisi)}, {}, ]
            $table->string('gejala_lain')->nullable();
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
        Schema::dropIfExists('gejala_pasien');
    }
}

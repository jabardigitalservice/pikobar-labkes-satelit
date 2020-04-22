<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatLawatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riwayat_lawatan', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('register_id');
            $table->unsignedBigInteger('pasien_id');
            $table->date('tanggal_lawatan');
            $table->string('nama_kota');
            $table->string('nama_negara');
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
        Schema::dropIfExists('riwayat_lawatan');
    }
}

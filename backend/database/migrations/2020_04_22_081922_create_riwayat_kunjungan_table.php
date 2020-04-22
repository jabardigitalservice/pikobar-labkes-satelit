<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiwayatKunjunganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riwayat_kunjungan', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('register_id');
            $table->json('riwayat'); // json array [{tanggal, fasyankes_id}, {..}, ...]
            $table->timestamps();

            $table->foreign('register_id')->references('id')->on('register');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('riwayat_kunjungan');
    }
}

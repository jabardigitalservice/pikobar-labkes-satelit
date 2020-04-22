<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemeriksaanPenunjangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemeriksaan_penunjang', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('register_id');
            $table->unsignedBigInteger('pasien_id');
            $table->boolean('xray_paru');
            $table->string('penjelasan_xray')->nullable();
            $table->decimal('leukosit')->nullable();
            $table->decimal('limfosit')->nullable();
            $table->decimal('trombosit')->nullable();
            $table->boolean('ventilator');
            $table->string('status_kesehatan'); // pulang, dirawat, meninggal
            $table->string('keterangan_lab')->nullable();

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
        Schema::dropIfExists('pemeriksaan_penunjang');
    }
}

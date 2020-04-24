<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengambilanSampelRegistrasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengambilan_sampel_registrasi', function (Blueprint $table) {
            $table->unsignedInteger('register_id')->index();
            $table->unsignedInteger('pengambilan_sampel_id')->index();


            $table->foreign('register_id')->references('id')->on('register');
            $table->foreign('pengambilan_sampel_id')->references('id')->on('pengambilan_sampel');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengambilan_sampel_registrasi');
    }
}

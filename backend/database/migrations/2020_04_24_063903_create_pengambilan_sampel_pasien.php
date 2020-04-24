<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengambilanSampelPasien extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengambilan_sampel_pasien', function (Blueprint $table) {
            $table->unsignedBigInteger('pasien_id')->index()->nullable();
            $table->unsignedInteger('pengambilan_sampel_id')->index();

            $table->foreign('pasien_id')->references('id')->on('pasien');
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
        Schema::dropIfExists('pengambilan_sampel_pasien');
    }
}

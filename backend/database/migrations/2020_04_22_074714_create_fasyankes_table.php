<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFasyankesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fasyankes', function (Blueprint $table) {
            $table->id();
            $table->string('tipe', 150); // Rumah sakit | Dinkes
            $table->string('nama');
            $table->unsignedInteger('kota_id')->nullable();
            $table->timestamps();

            $table->foreign('kota_id')->references('id')->on('kota');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fasyankes');
    }
}

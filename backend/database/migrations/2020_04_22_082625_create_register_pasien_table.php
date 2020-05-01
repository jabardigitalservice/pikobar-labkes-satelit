<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisterPasienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pasien_register', function (Blueprint $table) {
            // $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('register_id');
            $table->unsignedBigInteger('pasien_id');

            // $table->foreign('register_id')->references('id')->on('register');
            // $table->foreign('pasien_id')->references('id')->on('pasien');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pasien_register');
    }
}

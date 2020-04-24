<?php

use Facade\Ignition\Tabs\Tab;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sampel', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('pengambilan_sampel_id')->index()->nullable();
            $table->string('jenis_sampel', 100)->nullable();
            $table->string('petugas_pengambil_sampel')->nullable();
            $table->date('tanggal_sampel')->nullable();
            $table->time('waktu_sampel')->nullable();
            $table->string('nomor_barcode')->index()->nullable();
            $table->string('nama_diluar_jenis')->nullable();
            $table->string('status', 50);
            $table->timestamps();

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
        Schema::dropIfExists('sampel');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengambilanSampelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengambilan_sampel', function (Blueprint $table) {
            $table->id();
            $table->boolean('sampel_diambil')->nullable();
            $table->boolean('sampel_diterima');
            $table->boolean('diterima_dari_faskes');
            $table->string('penerima_sampel', 150)->nullable();
            $table->text('catatan')->nullable();
            $table->string('status', 50)->nullable();
            $table->string('nomor_ekstraksi')->nullable();
            $table->boolean('sampel_rdt');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengambilan_sampels');
    }
}

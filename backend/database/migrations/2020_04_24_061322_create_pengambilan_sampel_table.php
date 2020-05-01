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
            $table->boolean('sampel_diambil')->default(false);
            $table->boolean('sampel_diterima')->default(false);
            $table->boolean('diterima_dari_faskes')->default(false);
            $table->string('penerima_sampel', 150)->nullable();
            $table->string('sampel_id')->nullable();
            $table->text('catatan')->nullable();
            $table->string('status', 50)->nullable();
            $table->string('nomor_ekstraksi')->nullable();
            $table->string('sumber_sampel')->nullable();
            $table->boolean('sampel_rdt')->default(false);
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

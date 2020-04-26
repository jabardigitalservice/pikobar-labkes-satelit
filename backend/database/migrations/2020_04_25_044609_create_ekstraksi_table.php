<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEkstraksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ekstraksi', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('register_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->date('tanggal_penerimaan_sampel')->nullable();
            $table->string('jam_penerimaan_sampel')->nullable();
            $table->string('petugas_penerima_sampel')->nullable();
            $table->text('catatan_penerimaan')->nullable();
            $table->string('operator_ekstraksi')->nullable();
            $table->date('tanggal_mulai_ekstraksi')->nullable();
            $table->string('metode_ekstraksi')->nullable();
            $table->string('nama_kit_ekstraksi')->nullable();
            $table->string('nama_pengirim_rna')->nullable();
            $table->date('tanggal_pengiriman_rna')->nullable();
            $table->string('jam_pengiriman_rna')->nullable();
            $table->text('catatan_pegiriman')->nullable();
            $table->string('jam_mulai_ekstraksi')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('ekstraksi');
    }
}

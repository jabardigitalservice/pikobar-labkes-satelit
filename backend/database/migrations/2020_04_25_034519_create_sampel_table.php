<?php

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
        Schema::create('status_sampel', function (Blueprint $table) {
            $table->string('sampel_status')->primary();
            $table->string('chamber');
            $table->string('deskripsi');
            $table->timestamps();
        });
        Schema::create('jenis_sampel', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->timestamps();
        });
        Schema::create('sampel', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_sampel')->unique();
            $table->string('nomor_register')->index()->nullable();
            $table->unsignedInteger('register_id')->index()->nullable();
            $table->unsignedInteger('jenis_sampel_id')->index()->nullable();
            $table->unsignedInteger('pengambilan_sampel_id')->index()->nullable();
            $table->string('jenis_sampel_nama')->nullable();
            $table->string('sampel_status')->default('sample_taken')->index();
            $table->unsignedInteger('lab_pcr_id')->nullable();
            $table->string('lab_pcr_nama')->nullable();
            $table->unsignedInteger('creator_user_id')->nullable();
            $table->string('petugas_pengambilan_sampel')->nullable();
            $table->date('tanggal_pengambilan_sampel')->nullable();
            $table->string('jam_pengambilan_sampel')->nullable();
            $table->timestamp('waktu_waiting_sample')->index()->nullable();
            $table->timestamp('waktu_sample_taken')->index()->nullable();
            $table->timestamp('waktu_extraction_sample_extracted')->index()->nullable();
            $table->timestamp('waktu_extraction_sample_reextract')->index()->nullable();
            $table->timestamp('waktu_extraction_sample_sent')->index()->nullable();
            $table->timestamp('waktu_pcr_sample_received')->index()->nullable();
            $table->timestamp('waktu_pcr_sample_analyzed')->index()->nullable();
            $table->timestamp('waktu_sample_verified')->index()->nullable();
            $table->timestamp('waktu_sample_valid')->index()->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('creator_user_id')->references('id')->on('users');
            $table->foreign('lab_pcr_id')->references('id')->on('lab_pcr');
            $table->foreign('register_id')->references('id')->on('register');
            $table->foreign('sampel_status')->references('sampel_status')->on('status_sampel');
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
        Schema::dropIfExists('jenis_sampel');
        Schema::dropIfExists('status_sampel');
    }
}

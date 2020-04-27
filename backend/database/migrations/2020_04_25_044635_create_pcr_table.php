<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePcrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemeriksaansampel', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sampel_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->date('tanggal_penerimaan_sampel')->nullable();
            $table->string('jam_penerimaan_sampel')->nullable();
            $table->string('petugas_penerima_sampel_rna')->nullable();
            $table->string('operator_real_time_pcr')->nullable();
            $table->date('tanggal_mulai_pemeriksaan')->nullable();
            $table->string('jam_mulai_pcr')->nullable();
            $table->string('jam_selesai_pcr')->nullable();
            $table->string('metode_pemeriksaan')->nullable();
            $table->string('nama_kit_pemeriksaan')->nullable();
            $table->date('tanggal_input_hasil')->nullable();
            $table->string('jam_input_hasil')->nullable();
            $table->jsonb('target_gen')->nullable();
            $table->jsonb('hasil_deteksi')->nullable();
            $table->jsonb('grafik')->nullable();
            $table->string('kesimpulan_pemeriksaan')->nullable();
            $table->text('catatan_penerimaan')->nullable();
            $table->text('catatan_pemeriksaan')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('sampel_id')->references('id')->on('sampel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemeriksaansampel');
    }
}

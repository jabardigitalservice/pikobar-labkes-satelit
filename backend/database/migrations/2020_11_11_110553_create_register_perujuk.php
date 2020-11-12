<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisterPerujuk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_perujuk', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_register')->nullable();
            $table->string('register_uuid')->nullable();
            $table->integer('creator_user_id');
            $table->integer('lab_satelit_id');
            $table->integer('perujuk_id')
                ->unsigned()
                ->foreign('perujuk_id')
                ->references('id')
                ->on('perujuk')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
            $table->string('sumber_pasien');
            $table->integer('kriteria');
            $table->integer('swab_ke')->nullable();
            $table->date('tanggal_swab')->nullable();
            $table->string('nomor_sampel');
            $table->integer('jenis_sampel');
            $table->string('nama_jenis_sampel')->nullable();
            $table->integer('fasyankes_id');
            $table->string('fasyankes_pengirim');
            $table->string('nama_pasien');
            $table->enum('kewarganegaraan', ['WNI', 'WNA'])->default('WNI');
            $table->string('keterangan_warganegara')->nullable();
            $table->string('nik', 16)->nullable();
            $table->string('no_hp')->nullable();
            $table->integer('provinsi_id')->nullable();
            $table->integer('kota_id')->nullable();
            $table->integer('kecamatan_id')->nullable();
            $table->bigInteger('kelurahan_id')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->char('jenis_kelamin', 1)->nullable();
            $table->string('alamat')->nullable();
            $table->string('no_rt', 10)->nullable();
            $table->string('no_rw', 10)->nullable();
            $table->float('suhu')->nullable();
            $table->text('keterangan')->nullable();
            $table->integer('usia_tahun')->nullable();
            $table->integer('usia_bulan')->nullable();
            $table->enum('status', ['dikirim', 'diterima', 'hasil_pemeriksaan'])->default('dikirim');
            $table->index(['nomor_sampel']);
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
        Schema::table('register_perujuk', function (Blueprint $table) {
            $table->dropForeign('register_perujuk_perujuk_id_foreign');
            $table->dropIndex('nomor_sampel');
            $table->dropColumn('perujuk_id');
        });

        Schema::dropIfExists('register_perujuk');
    }
}

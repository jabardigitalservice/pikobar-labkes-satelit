<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pasien', function (Blueprint $table) {
            $table->bigIncrements('id');
            // $table->string('nama_depan');
            // $table->string('nama_belakang')->nullable();
            $table->string('nama_lengkap');
            $table->string('nik',16)->nullable()->index();
            $table->date('tanggal_lahir');
            $table->string('tempat_lahir', 100);
            $table->string('kewarganegaraan', 100);
            $table->string('no_hp', 25)->nullable();
            $table->string('no_telp', 20)->nullable();
            $table->string('pekerjaan', 150)->nullable();
            $table->char('jenis_kelamin', 1);
            $table->unsignedInteger('kota_id');
            $table->string('kecamatan', 100)->nullable();
            $table->string('kelurahan', 100)->nullable();
            $table->char('no_rw', 3)->nullable();
            $table->char('no_rt', 3)->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->text('keterangan_lain')->nullable();
            $table->double('suhu')->nullable();
            $table->string('sumber_pasien')->nullable();
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
        Schema::dropIfExists('pasien');
    }
}

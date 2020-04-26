<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRegisterStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_register', function (Blueprint $table) {
            $table->string('register_status')->unique();
            $table->string('chamber');
            $table->string('deskripsi');
            $table->timestamps();
        });

        Schema::table('register', function (Blueprint $table) {
            $table->dropColumn('nomor_register');
        });
        Schema::table('register', function (Blueprint $table) {
            $table->uuid('register_uuid')->unique();
            $table->enum('jenis_registrasi', ['mandiri','rujukan'])->default('mandiri')->index();
            $table->string('nomor_register')->unique();
            $table->string('nomor_sampel')->unique();
            $table->string('register_status')->default('waiting_sample')->index();
            $table->unsignedInteger('lab_pcr_id')->nullable();
            $table->string('lab_pcr_nama')->nullable();
            $table->unsignedInteger('creator_user_id')->nullable();
            $table->foreign('creator_user_id')->references('id')->on('users');
            $table->foreign('lab_pcr_id')->references('id')->on('lab_pcr');
            $table->foreign('register_status')->references('register_status')->on('status_register');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('register', function (Blueprint $table) {
            $table->dropForeign('creator_user_id_users_foreign');
            $table->dropForeign('users_status_register_register_status_foreign');
            $table->dropForeign('users_lab_pcr_id_foreign');
            $table->dropColumn('lab_pcr_id');
            $table->dropColumn('creator_user_id');
            $table->dropColumn('lab_pcr_nama');
            $table->dropColumn('register_status');
            $table->dropColumn('nomor_sampel');
            $table->dropColumn('nomor_register');
            $table->dropColumn('register_uuid');
            $table->dropColumn('jenis_registrasi');
        });
        Schema::dropIfExists('status_register');
    }
}

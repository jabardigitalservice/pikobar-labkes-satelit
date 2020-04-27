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

        Schema::table('register', function (Blueprint $table) {
            $table->dropColumn('nomor_register');
        });
        Schema::table('register', function (Blueprint $table) {
            $table->uuid('register_uuid')->unique();
            $table->enum('jenis_registrasi', ['mandiri','rujukan'])->default('mandiri')->index();
            $table->string('nomor_register')->unique();
            $table->unsignedInteger('creator_user_id')->nullable();
            $table->foreign('creator_user_id')->references('id')->on('users');
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
            $table->dropColumn('creator_user_id');
            $table->dropColumn('nomor_sampel');
            $table->dropColumn('nomor_register');
            $table->dropColumn('register_uuid');
            $table->dropColumn('jenis_registrasi');
        });
    }
}

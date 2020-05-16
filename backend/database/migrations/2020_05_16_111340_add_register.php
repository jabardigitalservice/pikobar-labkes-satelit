<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRegister extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('register', function (Blueprint $table) {
            $table->string('instansi_pengirim')->nullable();
        });
        Schema::table('register', function (Blueprint $table) {
            $table->string('instansi_pengirim_nama')->nullable();
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
            $table->dropColumn(['instansi_pengirim']);
        });
        Schema::table('register', function (Blueprint $table) {
            $table->dropColumn(['instansi_pengirim_nama']);
        });
    }
}

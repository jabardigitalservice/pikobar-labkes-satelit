<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipRegisterToFasyankes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('register', function (Blueprint $table) {
            $table->foreign('fasyankes_id')->references('id')->on('fasyankes');
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
            $table->dropForeign('register_fasyankes_id_foreign');
        });
    }
}

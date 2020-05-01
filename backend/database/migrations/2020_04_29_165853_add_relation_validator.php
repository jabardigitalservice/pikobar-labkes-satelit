<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationValidator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sampel', function (Blueprint $table) {
            $table->unsignedInteger('validator_id')->index()->nullable();

            $table->foreign('validator_id')->references('id')->on('validator');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('validator_id')->index()->nullable();

            $table->foreign('validator_id')->references('id')->on('validator');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['validator_id']);
        });

        Schema::table('sampel', function (Blueprint $table) {
            $table->dropColumn(['validator_id']);
        });
    }
}
